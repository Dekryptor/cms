<div class="row">
    <div class="col-md-6">
        <div class="block">
            <div class="header">
                <h2>{t("Items")}</h2>
            </div>
            <div class="content">
                {foreach from=$items item=item}
                {$lifetime = ""}
                {if $item->lifetime > 0}
                    {$lifetime = $item->lifetime}
                {/if}
                {$coin = "C."}
                {if $item->is_elite}
                    {$coin = "U."}
                {/if}
                <div class="col-md-3 item" onclick="updateDescription({$item->id})">
                    <img src="{$URL}img/items/{$item->category}/{$item->loot_id}.png">
                    <p>{$item->price} {$coin}/ {$lifetime}{$item->unity}</p>
                </div>
                {/foreach}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="block">
            <div class="header">
                <h2>{t("Description")}</h2>
            </div>
            <div class="content">
                <div class="col-md-6 item" id="preview">
                    {$lifetime = ""}
                    {if $items[0]->lifetime > 0}
                        {$lifetime = $items[0]->lifetime}
                    {/if}
                    {$coin = "C."}
                    {if $items[0]->is_elite}
                        {$coin = "U."}
                    {/if}
                    <img src="{$URL}img/items/{$items[0]->category}/{$items[0]->loot_id}.png">
                    <p>{$items[0]->price} {$coin} / {$lifetime}{$items[0]->unity}</p>
                </div>
                <div class="col-md-6" id="description">
                    <p>{t($items[0]->description)}</p>
                </div>
                <table class="table table-striped" id="stats">
                    {foreach from=json_decode($items[0]->stats) item=stat}
                    <tr>
                        <td>{t($stat->name)}</td>
                        <td>{$stat->value}</td>
                    </tr>
                    {/foreach}
                </table>
                <center><a href="{$URL}Internal/Shop/buy/{$items[0]->id}" id="buy" class="btn btn-default">{t("Buy")}</a></center>
            </div>
        </div>
    </div>
</div>
<script>
/**
 * Updates description.
 *
 * @param int id Item id.
 */
function updateDescription(id)
{
    jQuery.ajax({
        url: "{$URL}API/Items/item/"+ id,
        success: function(data) {
            if(data.result != "success") {
                console.log(data.error);
            }
            console.log(data);

            $("#preview").children("img").attr("src", data.item.image);
            $("#preview").children("p").text(data.item.price);
            $("#description").children("p").text(data.item.description);
            $("#stats").empty();
            for(var i = 0; i < data.item.stats.length; i++) {
                console.log(data.item.stats[i]);
                var tr   = $("<tr></tr>");
                var name = $("<td></td>").text(data.item.stats[i].name);
                var val  = $("<td></td>").text(data.item.stats[i].value);

                tr.append(name, val);

                $("#stats").append(tr);
            }
            $("#buy").attr("href", "{$URL}Internal/Shop/buy/"+ data.item.id);
        }
    });
}
</script>
