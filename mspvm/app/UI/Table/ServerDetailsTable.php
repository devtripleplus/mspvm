<?php namespace App\UI\Table;

use App\Server;
use App\VM;

Class ServerDetailsTable {
    public static function short(VM $vps) {
        return '<table>
        <tr>
        <td>
        RAM
</td>
<td>
256MB
</td>
</tr>
<tr>
<td>
SWAP
</td>
<td>
256MB
</td>
</tr>
</table>';
    }

    public static function detailed(Server $server) {
        return '<table>
        <tr>
        <td>
        IP
</td>
<td>
'.$server->ip.'
</td>
</tr>
<tr>
<td>
User
</td>
<td>
'.$server->user.'
</td>
</tr>
</table>';
    }
}