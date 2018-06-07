<?php

if (! function_exists('org_url')) {
    function org_url($path = null)
    {
        if (is_null($path)) {
            return env('IMPORTIRORG_API');
        }

        return env('IMPORTIRORG_API') . $path;
    }
}

if(!function_exists('to_float')){
    function to_float($string_number){
        // NOTE: You don't really have to use floatval() here, it's just to prove that it's a legitimate float value.
        $number = floatval(str_replace('.', ',', str_replace(',', '', $string_number)));

        // At this point, $number is a "natural" float.
        return $number;
    }
}

if(!function_exists('parseFloatComma')) {
    function parseFloatComma($numComma) {
        $number = str_replace(',', '', $numComma);

        return (float)$number;
    }
}


if(!function_exists('nav_url')){
    function nav_url(){
        $user       = Sentinel::check();
        $menus      = config('menu');
        $newMenu    = [];
        $subMenu    = [];
        foreach($menus as $menu){
            $isActive   = false;
            $asHeader   = !empty($menu['as']) ? $menu['as'] : null;
            if($user->hasAccess($asHeader . "*")){
                if(!empty($menu['sub']) AND count($menu['sub']) > 0){
                    foreach($menu['sub'] as $sub){
                        /*
                         * Hardcode for philip ev
                         */
                        if($sub['as'] == 'shipping-message.' AND $user->id == 40){
                            continue;
                        }
                        $asSub      = !empty($sub['as']) ? $sub['as'] : null;
                        if($user->hasAccess($asHeader . $asSub . "*")){
                            $subActive  = false;
                            $link       = !empty($sub['link']) ? $sub['link'] : null;
                            if(Request::is('backend/' . $link . '/*') OR Request::is('backend/' . $link)){
                                $isActive   = true;
                                $subActive  = true;
                            }

                            $sub['is_active']   = $subActive;

                            $subMenu[]          = $sub;
                        }
                    }
                }

                $menu['is_open']    = $isActive;
                $menu['sub']        = $subMenu;
                $subMenu            = [];   // reset

                $newMenu[]          = $menu;
            }
        }

        return $newMenu;
    }
}

if (! function_exists('getCity')) {
    function getCity()
    {
        $str    = "{\"ambon\":\"Ambon\",\"balikpapan\":\"Balikpapan\",\"banda aceh\":\"Banda Aceh\",\"bandar lampung\":\"Bandar Lampung\",\"bandung\":\"Bandung\",\"banjar\":\"Banjar\",\"banjarbaru\":\"Banjarbaru\",\"banjarmasin\":\"Banjarmasin\",\"batam\":\"Batam\",\"batu\":\"Batu\",\"bau-bau\":\"Bau-Bau\",\"bekasi\":\"Bekasi\",\"bengkulu\":\"Bengkulu\",\"bima\":\"Bima\",\"binjai\":\"Binjai\",\"bitung\":\"Bitung\",\"blitar\":\"Blitar\",\"bogor\":\"Bogor\",\"bontang\":\"Bontang\",\"bukittinggi\":\"Bukittinggi\",\"cilegon\":\"Cilegon\",\"cimahi\":\"Cimahi\",\"cirebon\":\"Cirebon\",\"denpasar\":\"Denpasar\",\"depok\":\"Depok\",\"dumai\":\"Dumai\",\"gorontalo\":\"Gorontalo\",\"jakarta\":\"Jakarta\",\"jambi\":\"Jambi\",\"jayapura\":\"Jayapura\",\"kediri\":\"Kediri\",\"kendari\":\"Kendari\",\"kupang\":\"Kupang\",\"langsa\":\"Langsa\",\"lhokseumawe\":\"Lhokseumawe\",\"lubuklinggau\":\"Lubuklinggau\",\"madiun\":\"Madiun\",\"magelang\":\"Magelang\",\"makassar\":\"Makassar\",\"malang\":\"Malang\",\"manado\":\"Manado\",\"mataram\":\"Mataram\",\"medan\":\"Medan\",\"metro\":\"Metro\",\"mojokerto\":\"Mojokerto\",\"padang\":\"Padang\",\"padang sidempuan\":\"Padang Sidempuan\",\"palangkaraya\":\"Palangkaraya\",\"palembang\":\"Palembang\",\"palopo\":\"Palopo\",\"palu\":\"Palu\",\"pangkalpinang\":\"Pangkalpinang\",\"parepare\":\"Parepare\",\"pasuruan\":\"Pasuruan\",\"pekalongan\":\"Pekalongan\",\"pekanbaru\":\"Pekanbaru\",\"pematangsiantar\":\"Pematangsiantar\",\"pontianak\":\"Pontianak\",\"prabumulih\":\"Prabumulih\",\"probolinggo\":\"Probolinggo\",\"salatiga\":\"Salatiga\",\"samarinda\":\"Samarinda\",\"semarang\":\"Semarang\",\"serang\":\"Serang\",\"singkawang\":\"Singkawang\",\"sorong\":\"Sorong\",\"surabaya\":\"Surabaya\",\"surakarta\":\"Surakarta\",\"tangerang\":\"Tangerang\",\"tangerang selatan\":\"Tangerang Selatan\",\"tanjungbalai\":\"Tanjungbalai\",\"tanjungpinang\":\"Tanjungpinang\",\"tarakan\":\"Tarakan\",\"tasikmalaya\":\"Tasikmalaya\",\"tebingtinggi\":\"Tebingtinggi\",\"tegal\":\"Tegal\",\"ternate\":\"Ternate\",\"yogyakarta\":\"Yogyakarta\"}";

        return json_decode($str);
    }
}

if (! function_exists('numFormat')) {
    function numFormat($numComma = "") {
        $number = str_replace(',', '', $numComma);

        return (float)$number;
    }
}

if (! function_exists('getLartas')) {
    function getLartas() {
        return [
            "non"       => "Non Lartas",
            "sni_7b"    => "SNI 7B",
            "sni_5a"    => "SNI 5A",
            "laporan_surveyor"  => "Laporan Surveyor"
        ];
    }
}

if(! function_exists('alertNotify')){
    function alertNotify($isSuccess  = true, $message = '', $request){
        if($isSuccess){
            $request->session()->flash('alert-class','success');
            $request->session()->flash('status', $message);
        }else{
            $request->session()->flash('alert-class','error');
            $request->session()->flash('status', $message);
        }
    }
}

if(!function_exists('hasGroup')){
    function hasGroup($groups, $groupID = null){
        if(is_null($groupID) OR !$groups OR $groups->count() == 0){
            return false;
        }

        foreach($groups as $group){
            if($group->group_id == $groupID OR $groupID == 1 /* is importir admin */){
                return true;
            }
        }

        return false;
    }
}

if(!function_exists('markingAir')){
    function markingAir($markingCode = ''){
        if (strpos(strtoupper($markingCode), 'SEA') !== false) {
            return str_replace('SEA','AIR', $markingCode);
        }

        return $markingCode;
    }
}
