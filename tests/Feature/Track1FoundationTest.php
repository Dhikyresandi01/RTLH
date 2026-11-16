<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureUserHasRole;
use App\Models\Bantuan;
use App\Models\User;
use App\Models\Warga;
use App\Policies\BantuanPolicy;
use App\Policies\WargaPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class Track1FoundationTest extends TestCase
{
    protected function makeUser(string $role, ?int $idWilayah = null): User
    {
        $user = new User();
        $user->id = match ($role) {
            'superadmin' => 1,
            'admin_dinas' => 2,
            default => 3,
        };
        $user->name = "User {$role}";
        $user->email = "{$role}@bansos.test";
        $user->role = $role;
        $user->id_wilayah = $idWilayah;

        return $user;
    }

    protected function makeWarga(int $idWilayah, ?int $statusBantuan = null): Warga
    {
        $warga = new Warga();
        $warga->id_warga = 100;
        $warga->id_wilayah = $idWilayah;

        if ($statusBantuan === null) {
            $warga->setRelation('bantuanAktif', null);
        } else {
            $bantuan = new Bantuan();
            $bantuan->id_bantuan = 200;
            $bantuan->id_warga = 100;
            $bantuan->status_bantuan = $statusBantuan;
            $bantuan->is_aktif = true;
            $warga->setRelation('bantuanAktif', $bantuan);
        }

        return $warga;
    }

    public function test_policies_are_auto_discovered_with_pascal_case_classes(): void
    {
        $this->assertInstanceOf(WargaPolicy::class, Gate::getPolicyFor(Warga::class));
        $this->assertInstanceOf(BantuanPolicy::class, Gate::getPolicyFor(Bantuan::class));
    }

    public function test_ensure_user_has_role_middleware_allows_permitted_role_and_aborts_forbidden_role(): void
    {
        $middleware = new EnsureUserHasRole();

        $allowedRequest = Request::create('/spk', 'GET');
        $allowedRequest->setUserResolver(fn () => $this->makeUser('admin_dinas'));

        $response = $middleware->handle(
            $allowedRequest,
            fn () => new Response('OK', 200),
            'superadmin',
            'admin_dinas'
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('OK', $response->getContent());

        $deniedRequest = Request::create('/spk', 'GET');
        $deniedRequest->setUserResolver(fn () => $this->makeUser('admin_kelurahan', 1));

        $this->expectException(HttpException::class);
        try {
            $middleware->handle(
                $deniedRequest,
                fn () => new Response('OK', 200),
                'superadmin',
                'admin_dinas'
            );
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
            throw $e;
        }
    }

    public function test_warga_policy_permissions_by_role_wilayah_and_status(): void
    {
        $policy = new WargaPolicy();

        $superadmin = $this->makeUser('superadmin');
        $adminDinas = $this->makeUser('admin_dinas');
        $kelurahanA = $this->makeUser('admin_kelurahan', 10);
        $kelurahanNull = $this->makeUser('admin_kelurahan', null);

        $wargaA = $this->makeWarga(10);
        $wargaB = $this->makeWarga(20);

        // Create: hanya superadmin & admin_kelurahan
        $this->assertTrue($policy->create($superadmin));
        $this->assertTrue($policy->create($kelurahanA));
        $this->assertFalse($policy->create($adminDinas));

        // View: superadmin & admin_dinas lintas wilayah; kelurahan hanya wilayah sendiri
        $this->assertTrue($policy->view($superadmin, $wargaB));
        $this->assertTrue($policy->view($adminDinas, $wargaB));
        $this->assertTrue($policy->view($kelurahanA, $wargaA));
        $this->assertFalse($policy->view($kelurahanA, $wargaB));
        $this->assertFalse($policy->view($kelurahanNull, $wargaA));

        // Update: superadmin true; admin_dinas false; kelurahan wilayah sama hanya tanpa bantuan atau status 0, 1, 3
        $this->assertTrue($policy->update($superadmin, $this->makeWarga(20, Bantuan::STATUS_DIREALISASIKAN)));
        $this->assertFalse($policy->update($adminDinas, $this->makeWarga(10, Bantuan::STATUS_DISETUJUI)));

        $this->assertTrue($policy->update($kelurahanA, $this->makeWarga(10, null)));
        foreach ([Bantuan::STATUS_DIAJUKAN, Bantuan::STATUS_DIVERIFIKASI, Bantuan::STATUS_REVISI] as $editableStatus) {
            $this->assertTrue($policy->update($kelurahanA, $this->makeWarga(10, $editableStatus)));
        }

        foreach ([Bantuan::STATUS_DISETUJUI, Bantuan::STATUS_DITOLAK, Bantuan::STATUS_DIREALISASIKAN] as $lockedStatus) {
            $this->assertFalse($policy->update($kelurahanA, $this->makeWarga(10, $lockedStatus)));
        }

        $this->assertFalse($policy->update($kelurahanA, $this->makeWarga(20, Bantuan::STATUS_DIAJUKAN)));
        $this->assertFalse($policy->update($kelurahanNull, $this->makeWarga(10, Bantuan::STATUS_DIAJUKAN)));

        // Delete: hanya superadmin
        $this->assertTrue($policy->delete($superadmin, $wargaA));
        $this->assertFalse($policy->delete($adminDinas, $wargaA));
        $this->assertFalse($policy->delete($kelurahanA, $wargaA));
    }

    public function test_bantuan_policy_permissions(): void
    {
        $policy = new BantuanPolicy();

        $superadmin = $this->makeUser('superadmin');
        $adminDinas = $this->makeUser('admin_dinas');
        $kelurahanA = $this->makeUser('admin_kelurahan', 10);
        $kelurahanB = $this->makeUser('admin_kelurahan', 20);

        $wargaA = $this->makeWarga(10);
        $bantuan = new Bantuan();
        $bantuan->setRelation('warga', $wargaA);

        $bantuanOrphan = new Bantuan();
        $bantuanOrphan->setRelation('warga', null);

        $this->assertTrue($policy->view($superadmin, $bantuan));
        $this->assertTrue($policy->view($adminDinas, $bantuan));
        $this->assertTrue($policy->view($kelurahanA, $bantuan));
        $this->assertFalse($policy->view($kelurahanB, $bantuan));
        $this->assertFalse($policy->view($kelurahanA, $bantuanOrphan));

        $this->assertTrue($policy->create($superadmin));
        $this->assertTrue($policy->create($kelurahanA));
        $this->assertFalse($policy->create($adminDinas));

        $this->assertTrue($policy->verify($superadmin));
        $this->assertTrue($policy->verify($adminDinas));
        $this->assertFalse($policy->verify($kelurahanA));

        $this->assertTrue($policy->recordRealization($superadmin));
        $this->assertTrue($policy->recordRealization($adminDinas));
        $this->assertFalse($policy->recordRealization($kelurahanA));
    }

    public function test_bantuan_status_helpers_and_accessors(): void
    {
        $this->assertSame(
            [Bantuan::STATUS_DIAJUKAN, Bantuan::STATUS_DIVERIFIKASI, Bantuan::STATUS_REVISI],
            Bantuan::STATUS_EDITABLE_KELURAHAN
        );

        $expected = [
            Bantuan::STATUS_DIAJUKAN => ['Menunggu Verifikasi', 'bg-secondary text-white', true],
            Bantuan::STATUS_DIVERIFIKASI => ['Diverifikasi Lapangan', 'bg-info text-dark', true],
            Bantuan::STATUS_DISETUJUI => ['Disetujui Dinas', 'bg-primary text-white', false],
            Bantuan::STATUS_REVISI => ['Perlu Revisi', 'bg-warning text-dark', true],
            Bantuan::STATUS_DITOLAK => ['Ditolak', 'bg-danger text-white', false],
            Bantuan::STATUS_DIREALISASIKAN => ['Direalisasikan', 'bg-success text-white', false],
        ];

        $this->assertSame(
            array_map(fn ($item) => $item[0], $expected),
            Bantuan::statusLabels()
        );

        foreach ($expected as $code => [$label, $badgeClass, $editable]) {
            $bantuan = new Bantuan();
            $bantuan->status_bantuan = $code;

            $this->assertSame($label, $bantuan->status_label);
            $this->assertSame($badgeClass, $bantuan->status_badge_class);
            $this->assertSame($editable, $bantuan->isEditableByKelurahan());
        }

        $unknown = new Bantuan();
        $unknown->status_bantuan = null;
        $this->assertSame('Tidak Diketahui', $unknown->status_label);
        $this->assertSame('bg-light text-muted', $unknown->status_badge_class);
    }

    public function test_routes_and_role_middleware_are_configured_correctly(): void
    {
        $this->assertTrue(Route::has('warga.index'));
        $this->assertTrue(Route::has('warga'));
        $this->assertTrue(Route::has('warga.disetujui'));
        $this->assertTrue(Route::has('verifikasi.index'));
        $this->assertTrue(Route::has('spk.index'));
        $this->assertTrue(Route::has('realisasi.index'));
        $this->assertTrue(Route::has('program-bantuan.index'));

        $routes = Route::getRoutes();

        $this->assertContains('role:superadmin', $routes->getByName('program-bantuan.index')->gatherMiddleware());
        $this->assertContains('role:superadmin', $routes->getByName('program-bantuan.store')->gatherMiddleware());
        $this->assertContains('role:superadmin,admin_dinas', $routes->getByName('verifikasi.index')->gatherMiddleware());
        $this->assertContains('role:superadmin,admin_dinas', $routes->getByName('spk.index')->gatherMiddleware());
        $this->assertContains('role:superadmin,admin_dinas', $routes->getByName('spk.hitung')->gatherMiddleware());
        $this->assertContains('role:superadmin,admin_dinas', $routes->getByName('spk.verifikasi')->gatherMiddleware());
        $this->assertContains('role:superadmin,admin_dinas', $routes->getByName('realisasi.index')->gatherMiddleware());
        $this->assertContains('role:superadmin,admin_dinas', $routes->getByName('realisasi.update')->gatherMiddleware());
        $this->assertContains('role:superadmin,admin_dinas', $routes->getByName('realisasi.destroy')->gatherMiddleware());

        $kelurahan = $this->makeUser('admin_kelurahan', 1);
        $adminDinas = $this->makeUser('admin_dinas');
        $superadmin = $this->makeUser('superadmin');

        // Legacy /Warga -> 301 redirect ke /warga dengan query string
        $this->actingAs($kelurahan)
            ->get('/Warga?search=andi')
            ->assertStatus(301)
            ->assertRedirect('/warga?search=andi');

        // /verifikasi -> 302 redirect ke /spk untuk dinas/superadmin, 403 untuk kelurahan
        $this->actingAs($kelurahan)->get('/verifikasi')->assertStatus(403);
        $this->actingAs($adminDinas)
            ->get('/verifikasi?tahun=2026')
            ->assertStatus(302)
            ->assertRedirect('/spk?tahun=2026');
        $this->actingAs($superadmin)
            ->get('/verifikasi')
            ->assertStatus(302)
            ->assertRedirect('/spk');

        // Route terlarang berdasarkan role
        $this->actingAs($kelurahan)->get('/program-bantuan')->assertStatus(403);
        $this->actingAs($adminDinas)->get('/program-bantuan')->assertStatus(403);
        $this->actingAs($kelurahan)->get('/spk')->assertStatus(403);
        $this->actingAs($kelurahan)->post('/spk/hitung')->assertStatus(403);
        $this->actingAs($kelurahan)->get('/realisasi')->assertStatus(403);
    }
}
