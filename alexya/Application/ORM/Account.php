<?php
namespace Application\ORM;

use \Alexya\Container;
use \Alexya\Database\ORM\Model as ORM;

use \Application\Handler\Account\Messaging;

/**
 * Account object.
 *
 * Represents user's account from database.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Account extends ORM
{
    ///////////////////////////////////
    // Static methods and properties //
    ///////////////////////////////////

    /**
     * ORM relations.
     *
     * @var array
     */
    protected static $_relations = [
        "accounts_equipment_hangars" => [
            "setRelations" => true,
            "name" => "Hangar"
        ],
        "factions" => [
            "name" => "Faction"
        ],
        "ranks" => [
            "name" => "Rank"
        ],
        "clans" => [
            "name"      => "Clan",
            "condition" => "\\Aplication\\ORM\\Account::canSetClan"
        ],
        /*"maps" => [
            "name" => "Map",
            "localKey" => "id"
        ],*/
        "levels" => [
            "name" => "Level"
        ]
    ];

    /**
     * Checks if the account can set ORM relation clan.
     *
     * @param array $columns Database columns.
     */
    public static function canSetClan(array $columns) : bool
    {
        return ($columns["clans_id"] > 0);
    }

    ///////////////////////////////////////
    // Non static methods and properties //
    ///////////////////////////////////////

    /**
     * OnInstance method.
     *
     * Sets user's messages handler.
     */
    public function onInstance()
    {
        $inbox = ORM::all([
            "to_accounts_id" => $this->id
        ], "accounts_messages");

        $outbox = ORM::all([
            "from_accounts_id" => $this->id
        ], "accounts_messages");

        $this->_data["Messaging"] = new Messaging($inbox, $outbox);
        $this->_data["Map"]       = ORM::find(1, -1, "maps");
    }

    /**
     * Checks that the user is logged in.
     *
     * @return bool `true` if account is 100% instantiated (aka user is logged in), `false` if not.
     */
    public function isLogged() : bool
    {
        return !empty($this->_data["id"]);
    }

    /**
     * Checks that the account has successfully verified the
     * invitation code.
     *
     * @return bool `true` if account has verified the invitation code, `false` if not.
     */

    public function hasVerifiedInvitationCode() : bool
    {
        // TODO compare invitation code with database
        return (Container::Session()->invitation["verified"] ?? false);
    }
}
