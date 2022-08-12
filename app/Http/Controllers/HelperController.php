<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Municipality;
use App\Models\Barangay;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use PDF;

class HelperController extends Controller
{
    public static function pdfdownload(Request $request) {
        $name = $request->input('name');
        $id = $request->input('id');
        $address = $request->input('address');
        $qrcode_size = $request->input('qrcode_size');
        $img = $request->input('img');
        if(($name) and ($id)) {
            $data = [
                'name' => ucwords($name),
                'id' => $id,
                'address' => ucwords($address),
                'qrcode_size' => $qrcode_size,
                'img' => $img
            ];
            $customPaper = array(0,0,750.00,450.00);
            $pdfcontent = PDF::loadView('pages.pdf', $data)->setPaper($customPaper, 'portrait')->output();
            $filename = $id.'.pdf';
            return response()->streamDownload(function() use ($pdfcontent) {
                print($pdfcontent);
            }, $filename);
        }
    }
    public static function getMax() {
        $max = 0;
        $arr = [];
        $mun = Municipality::orderBy('municipality_name')->get();
        foreach($mun as $m) {
           $arr[] = User::where('municipality', $m->municipality_code_number)->count();
        }
        return max($arr);
    }
    public static function getFieldValue($model, $field, $id) {
        if($model == "Municipality") {
            $singleModel = Municipality::where('municipality_code_number', $id)->first();
        } else if($model == "Barangay") {
            $singleModel = Barangay::find($id);
        } 
        if($singleModel != null) {
            return $singleModel->$field;
        } else {
            return '';
        }
    }
    public function populateData($type, $code, $name) {
        $name = str_replace("-"," ", $name);
        if($type == "municipality") {
            $mun = User::where('municipality', $code)->update(['municipality_name' => ucwords(strtolower($name))]);
        } else if($type == "province") {
            $prov = User::where('province', $code)->update(['province_name' => ucwords(strtolower($name))]);
        } else if($type == "barangay") {
            $prov = User::where('barangay', $code)->update(['barangay_name' => ucwords(strtolower($name))]);
        }
        return redirect()->route('home');
    }
    public function populateData2($type, $mun, $bar) {
        $mun = str_replace("-"," ", $mun);
        $m = Municipality::where('municipality_name', 'like', '%'.$mun.'%')->first();
        if($type == "municipality") {
            $mn = User::where('municipality', $m->municipality_code_number)->update(['municipality_name' => ucwords(strtolower($mun))]);
        } else if($type == "province") {
            $prov = User::where('province', $code)->update(['province_name' => ucwords(strtolower($name))]);
        } else if($type == "barangay") {
            $bar = str_replace("-"," ", $bar);
            $br = Barangay::where('barangay_name', 'like', '%'.$bar.'%')->where('municipality_code_number', $m->municipality_code_number)->first();
            $b = User::where('barangay', $br->id)->update(['barangay_name' => ucwords(strtolower($bar))]);
        }
        return redirect()->route('home');
    }
    public function birth($year) {
        $byear = User::where('birth_year', null)->update(['birth_year' => $year]);
        return redirect()->route('home');
    }
    public static function getRole($role) {
        $rl = Role::where('name', 'like', '%'.$role.'%')->first();
        $rt = "";
        if($rl) {
            $rt = $rl->name;
        }
        return $rt;
    }
    public static function getRolePermissions($role) {
        $roles = Role::findByName($role)->permissions;
        $cntr = $roles->count();
        $str = "";
        $j = 0;
        if($roles) {
            foreach($roles as $role) {
                if($cntr <= 1) {
                    $str .= $role->name;
                } else {
                    if($j >= $cntr) {
                        $str .= $role->name;
                    } else {
                        $str .= $role->name. ', ';
                    }
                }
                $j++;
            }
        }
        return $str;
    }
    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
    public function municipality($mun) {
        $mun = str_replace("-"," ", $mun);
        $m = Municipality::where('municipality_name', 'like', '%'.$mun.'%')->first();
        if($m) {
            return Excel::download(new UsersExport('municipality', '', $m->municipality_code_number), 'users_municipality.xlsx');
        } else {
            return redirect()->route('home');
        }
    }
    public function barangay($mun, $bar) {
        $mun = str_replace("-"," ", $mun);
        $bar = str_replace("-"," ", $bar);
        $m = Municipality::where('municipality_name', 'like', '%'.$mun.'%')->first();
        if($m) {
            $b = Barangay::where('barangay_name', 'like', '%'.$bar.'%')->where('municipality_code_number', $m->municipality_code_number)->first();
            if($b) {
                return Excel::download(new UsersExport('barangay', $m->municipality_code_number, $b->id), 'users_barangay.xlsx');
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('home');
        }
    }
    public function export() {
        return Excel::download(new UsersExport('all', '', ''), 'users.xlsx');
    }
    public static function generateID($mun, $bar, $dist) {
        $municipality = Municipality::where('municipality_code_number', $mun)->first();
        $cntr = User::where('id_number', '!=', null)->where('municipality', $mun)->where('district_no', $dist)->count();
        $ID = "";
        $num = (int)$cntr + 1;
        $len = strlen((string)$num);
        $code_name = $municipality->municipality_code_name;
        $dist_no = "LD";
        if($dist != "LD") {
            $dist_no = "00".$dist;
        }
        if($len == 1) {
            $ID = $dist_no."-".$code_name."-"."00000".(string)$num;
        } else if($len == 2) {
            $ID = $dist_no."-".$code_name."-"."0000".(string)$num;
        } else if($len == 3) {
            $ID = $dist_no."-".$code_name."-"."000".(string)$num;
        } else if($len == 4) {
            $ID = $dist_no."-".$code_name."-"."00".(string)$num;
        } else if($len == 5) {
            $ID = $dist_no."-".$code_name."-"."0".(string)$num;
        } else if($len == 6) {
            $ID = $dist_no."-".$code_name."-".(string)$num;
        } else {
            $ID = "limit";
        }
        return $ID;
    }
    public static function convertObjectToString($obj, $delimeter) {
        $str = "";
        $cntr = $obj->count();
        $i = 0;
        if($cntr > 0) {
            foreach($obj as $ob) {
                if($i == $cntr - 1) {
                    $str .= $ob->name;
                } else {
                    $str .= $ob->name.$delimeter;
                }
                $i++;
            }
        }
        return $str;
    }
    public static function convertArrayToString($arr, $delimeter) {
        $rolls = '';
        $cntr = $arr->count();
        if($cntr > 0) {
            for($i=0;$i<$cntr;$i++) {
                if($i == $cntr - 1) {
                    $rolls .= $arr[$i];    
                } else {
                    $rolls .= $arr[$i] .$delimeter.' ';
                }
            }
        }
        return $rolls;
    }
}
