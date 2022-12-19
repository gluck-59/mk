<li id="recent_{$item.index}">
    <table width="100%" border="0"; >
        <tr>
            <td valign="middle"; height="90px" width="87px">
                <a href="{$item.product_link}">
                    <img class="recentorders" src="{$item.img}" alt="{$item.product_name}">
                </a>
            </td>

            <td valign="middle"; height="90px">
                <a rel="nofollow" href="{$item.product_link}">{$item.product_name|truncate:15}
                    <br>
                    Едет в {$item.address|truncate:15}
                    <br> байкеру {$item.biker}
                </a>
            </td>
        </tr>
    </table>
</li>  