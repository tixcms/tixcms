<tr>
    <td>
        <?=$item['path']?>
    </td>
    <td style="width: 15%; text-align: center;">
        <?=$item['value'] 
            ? '<span class="label label-success">OK</span>' 
            : '<span class="label label-warning">нет прав на запись</span>'?>
    </td>
</tr>