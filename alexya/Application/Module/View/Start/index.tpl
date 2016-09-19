<div class="row">
    <div class="col-md-4">
        <div class="block">
            <div class="header">
                <h2>{t("General Stats")}</h2>
            </div>
            <div class="content">
                <div class="head-panel nm">
                    <div class="hp-info pull-left" style="padding-bottom: 10px;">
                        <span class="hp-main">{t("Username")}</span>
                        <span class="hp-main">{t("User since")}</span>
                        <span class="hp-main">{t("Rank")}</span>
                        <span class="hp-main">{t("Map")}</span>
                        <span class="hp-main">{t("Company")}</span>
                    </div>
                    <div class="hp-info pull-left">
                        <span class="hp-sm">{$user->name}</span>
                        <span class="hp-sm">{$user->register_date}</span>
                        <span class="hp-sm">{$user->rank_position}</span>
                        <span class="hp-sm">{$user->Map->name}</span>
                        <span class="hp-sm" style="color: #{$user->Faction->color}"><i class="fa fa-circle"></i>{strtoupper($user->Faction->abbreviation)}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="block">
            <div class="header">
                <h2>{t("Currency Stats")}</h2>
            </div>
            <div class="content">
                <div class="head-panel nm">
                    <div class="hp-info pull-left">
                        <span class="hp-main">{t("Credits")}</span>
                        <span class="hp-main">{t("Uridium")}</span>
                        <span class="hp-main">{t("Experience")}</span>
                        <span class="hp-main">{t("Level")}</span>
                        <span class="hp-main">{t("Honor")}</span>
                    </div>
                    <div class="hp-info pull-left">
                        <span class="hp-sm">{$user->credits}</span>
                        <span class="hp-sm">{$user->uridium}</span>
                        <span class="hp-sm">{$user->experience}</span>
                        <span class="hp-sm">{$user->Level->id}</span>
                        <span class="hp-sm">{$user->honor}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="block">
            <div class="header">
                <h2>{t("Clan Stats")}</h2>
            </div>
            <div class="content">
                {if $user->clans_id != 0}
                <div class="head-panel nm">
                    <div class="hp-info pull-left" style="padding-bottom: 10px;">
                        <span class="hp-main">{t("Clan name")}</span>
                        <span class="hp-main">{t("Tag")}</span>
                        <span class="hp-main">{t("Members")}</span>
                        <span class="hp-main">{t("Rank position")}</span>
                        <span class="hp-main">{t("Best rank position")}</span>
                    </div>
                    <div class="hp-info pull-left">
                        <span class="hp-sm">{$user->Clan->name}</span>
                        <span class="hp-sm">{$user->Clan->tag}</span>
                        <span class="hp-sm">{$user->Clan->members}</span>
                        <span class="hp-sm">{$user->Clan->rank_position}</span>
                        <span class="hp-sm">{$user->Clan->rank_best_position}</span>
                    </div>
                </div>
                {else}
                <div class="head-panel nm">
                    <span class="hp-main"><a href="{$URL}Internal/Clan">{t("Join a clan now!")}</a></span>
                </div>
                {/if}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="block">
            <div class="header">
                <h2>{t("Near events")}</h2>
            </div>
            <div class="content">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{t("Name")}</th>
                            <th>{t("Start time")}</th>
                            <th>{t("End time")}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=\Alexya\Database\ORM\Model::latest(7, "date_start", "events") item=event}
                        <tr>
                            <td>{$event->name}</td>
                            <td>{$event->date_start}</td>
                            <td>{$event->date_end}</td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="block">
            <div class="header">
                <h2>{t("User ranking")}</h2>
            </div>
            <div class="content">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{t("Name")}</th>
                            <th>{t("Faction")}</th>
                            <th>{t("Ranking")}</th>
                            <th>{t("Points")}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=\Application\ORM\Account::latest(9, "rank_points") item=account name="user_rank"}
                        <tr>
                            <td>{$account->name}</td>
                            <td style="color: {$account->Faction->color}"><span class="fa fa-circle"></span>{strtoupper($account->Faction->abbreviation)}</td>
                            <td>{$smarty.foreach.user_rank.iteration}</td>
                            <td>{$account->rank_points}</td>
                        </tr>
                        {/foreach}
                        <tr>
                            <td colspan="4" style="text-align: center;"><b>{t("Your ranking")}</b></td>
                        </tr>
                        <tr>
                            <td>{$user->name}</td>
                            <td style="color: {$user->Faction->color}"><span class="fa fa-circle"></span>{strtoupper($user->Faction->abbreviation)}</td>
                            <td>{$user->rank_position}</td>
                            <td>{$user->rank_points}</td>
                        </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="block">
            <div class="header">
                <h2>{t("Clan ranking")}</h2>
            </div>
            <div class="content">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{t("Name")}</th>
                            <th>{t("Tag")}</th>
                            <th>{t("Ranking")}</th>
                            <th>{t("Points")}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$amount = 10}
                        {if $user->clans_id != 0}
                            {$amount = 9}
                        {/if}
                        {foreach from=\Alexya\Database\ORM\Model::latest($amount, "rank_points", "clans") item=clan name="clan_rank"}
                        <tr>
                            <td>{$clan->name}</td>
                            <td>{$clan->tag}</td>
                            <td>{$smarty.foreach.clan_rank.iteration}</td>
                            <td>{$clan->rank_points}</td>
                        </tr>
                        {/foreach}
                        {if $user->clans_id != 0}
                        <tr>
                            <td colspan="4" style="text-align: center;"><b>{t("Your clan ranking")}</b></td>
                        </tr>
                        <tr>
                            <td>{$user->Clan->name}</td>
                            <td>{$user->Clan->tag}</td>
                            <td>{$user->Clan->rank_position}</td>
                            <td>{$user->Clan->rank_points}</td>
                        </tr>
                        {/if}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
