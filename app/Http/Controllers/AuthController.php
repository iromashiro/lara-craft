<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\IsiTabel;
use App\Models\JenisTabel;
use App\Models\Opd;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use stdClass;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{

    public function viewLogin()
    {
        return view("login.main", [
            "title" => "Login user"
        ]);
    }

    public function viewRegister($id_opd = null)
    {
        $opdBelumTerpakai = DB::table("opd")
            ->leftJoin("user", "opd.id", "=", "user.id_opd")
            ->where("user.id_opd", "=", null)
            ->get("opd.*");

        return view("register", [
            "title" => "Register user untuk opd",
            "opd_belum_pakai" => $opdBelumTerpakai,
            "id_opd" => $id_opd
        ]);
    }


    public function viewListOpd()
    {
        $listAllOpd =  DB::select(DB::raw("select o.id,o.nama_opd,u.username,(select count(id) from jenis_tabel where id_opd = o.id ) as jumlah_tabel from opd o left join
        public.user u on u.id_opd = o.id"));

        return view("opd.list_opd", compact("listAllOpd"), [
            "title" => "List seluruh OPD"
        ]);
    }



    public function viewBuatOpd()
    {
        return view("buat_opd", [
            'title' => "Tambah opd baru"
        ]);
    }


    public function submitBuatOpd(Request $req)
    {
        try {
            $newOpd = new Opd();
            $newOpd->nama_opd = $req->nama_opd;
            $newOpd->save();


            return redirect()
                ->back()
                ->with("pesan", "Tambah opd berhasil");
        } catch (Exception $ex) {
            return redirect()
                ->back()
                ->with("pesan", "Tambah opd gagal {$ex->getMessage()}");
        }
    }

    public function lihatIsiTabelPerOpd(Request $req, $id_jenis_tabel = null)
    {
        try {
            $getJenisTabel = JenisTabel::find($id_jenis_tabel);
            $getIsiTabel = IsiTabel::where("id_jenis_tabel", $getJenisTabel->id)->get()->toArray();



            $data = new stdClass;
            $data->nama_tabel = $getJenisTabel->nama_tabel;
            $data->nama_kolom = json_decode($getJenisTabel->nama_kolom);
            $mappedIsiTabel = array_map(function ($v) {
                return json_decode($v["data"]);
            }, $getIsiTabel);
            $data->isiTabel = $mappedIsiTabel;




            return view("admin.data_isitabel_opd", [
                "title" => "Isi Tabel",
                "data" => $data
            ]);
        } catch (Exception $ex) {
            return view("admin.data_isitabel_opd", [
                "title" => "Isi Tabel",
                "data" => null
            ]);
        }
    }

    public function listTabelPerOpd(Request $req, $id_opd = null)
    {
        try {
            $allJenisTabel = JenisTabel::all()->where("id_opd", $id_opd);
            $getOpd = Opd::find($id_opd);

            $obj = new stdClass;
            $obj->nama_opd = $getOpd->nama_opd;
            $obj->jenis_tabel = $allJenisTabel;

            return view("admin.list_jenistabel_opd", [
                'title' => 'List Tabel',
                'data' => $obj
            ]);
        } catch (Exception $ex) {
            return view("admin.list_jenistabel_opd", [
                "title" => "List Tabel",
                'data' => null
            ]);
        }
    }


    public function submitLogin(Request $req)
    {
        try {
            $getUser = User::where("username", $req->username)->first();

            if (!$getUser) {
                throw new Exception("Error, username atau password salah");
            }

            $isValid = Hash::check($req->password, $getUser->password);
            if (!$isValid) {
                throw new Exception("Error, username atau password salah");
            }

            $req->session()->put("is_login", true);
            $idOpd = $getUser->id_opd;

            if ($idOpd) {
                $req->session()->put("is_opd", true);
                $req->session()->put("id_opd", $idOpd);
            } else {
                $req->session()->put("is_admin", true);
            }
            Alert::success('Login Berhasil!', 'Anda Telah Login');

            return redirect()->route('dashboard-overview-3');
        } catch (Exception $ex) {
            Alert::error('Login Gagal!', 'Username atau Password Salah!');
            return redirect()
                ->back();
        }
    }

    public function submitRegister(Request $req)
    {
        try {
            $newUser = new User();

            $newUser->username = $req->username;
            $newUser->password = Hash::make($req->password);
            $newUser->id_opd = $req->id_opd;

            $newUser->save();

            return redirect()
                ->back()
                ->with("pesan", "berhasil mendaftarkan user untuk opd");
        } catch (Exception $ex) {
            return redirect()
                ->back()
                ->with("pesan", "Gagal, mendaftarkan user untuk opd");
        }
    }


    public function logout(Request $req)
    {
        $req->session()->flush();
        return redirect()
            ->route("view_login")
            ->with("pesan", "Anda berhasil logout");
    }

    public function list_admin()
    {
        $get_user = User::all()->where("id_opd", "!=", null);

        return \view('opd.list_admin', ['title' => 'List Admin'], \compact('get_user'));
    }
}
