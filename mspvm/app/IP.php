<?php namespace App;

use Illuminate\Database\Eloquent\Model;

Class IP extends Model   {
    protected $table = 'ipaddresses';

    protected $fillable = [
        'ip_address',
        'vps_id'
    ];

    public function hasVM() {
        return $this->vps_id != 0;
    }

    public function vm() {
        return $this->hasOne('App\VM', 'id', 'vps_id')->first();
    }

    private static function cidrToArray($ip_addr_cidr) {
        $ip_arr = explode("/", $ip_addr_cidr);
        $bin = "";

        if (!isset($ip_arr[1])) {
            return [
                $ip_addr_cidr
            ];
        }

        for($i=1;$i<=32;$i++) {
            $bin .= $ip_arr[1] >= $i ? '1' : '0';
        }

        $ip_arr[1] = bindec($bin);

        $ip = ip2long($ip_arr[0]);
        $nm = $ip_arr[1];
        $nw = ($ip & $nm);
        $bc = $nw | ~$nm;
        $bc_long = ip2long(long2ip($bc));

        for($zm=1;($nw + $zm)<=($bc_long - 1);$zm++)
        {
            $ret[]=long2ip($nw + $zm);
        }
        return $ret;
    }

    public static function create(array $data = []) {
        $ips = [];

        $addresses = static::cidrToArray($data['address']);

        // remove broadcast
        unset($addresses[count($addresses) - 1]);

        // remove gateway
        unset($addresses[0]);

        foreach ($addresses as $address) {
            $ips[] = parent::create([
                'ip_address' => $address
            ]);
        }

        return $ips;
    }
}