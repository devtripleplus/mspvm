<?php namespace App\UI\Table;

use App\VM;

Class VMDetailsTable {
    public static function short(VM $vps) {
        return '<table>
        <tr>
        <td>
        Hostname
</td>
<td>
'.$vps->hostname.'
</td>
</tr>
<tr>
<td>
RAM
</td>
<td>
'.$vps->ram.'
</td>
</tr>
</table>';
    }

    public static function detailed(VM $vm) {
        return '<table>
        <tr>
        <td>
        Hostname
</td>
<td>
'.$vm->hostname.'
</td>
</tr>
        <tr>
        <td>
        CTID
</td>
<td>
'.$vm->virt_identifier.'
</td>
</tr>
<tr>
<td>
RAM
</td>
<td>
'.$vm->ram.'
</td>
</tr>
<tr>
<td>
CPUs
</td>
<td>
'.$vm->cpus.'
</td>
</tr>
</table>';
    }
}