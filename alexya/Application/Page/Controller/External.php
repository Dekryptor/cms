<?php
namespace Application\Page\Controller;

use \Alexya\Container;
use \Alexya\Database\QueryBuilder;
use \Alexya\Foundation\Controller;
use \Alexya\Http\Response;
use \Alexya\Tools\{
    Session\Results,
    Str
};
use \Alexya\Validator\Validator;

/**
 * External page controller.
 *
 * Contains the logic of the external page.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class External extends Controller
{
    /**
     * Default action.
     *
     * Finds the home page and renders it.
     *
     * @return string Login page content.
     */
    public function index() : string
    {
        return $this->Login();
    }

    /**
     * Renders and returns the InvitationCode page.
     *
     * @return string Invitation code page content.
     */
    public function InvitationCode() : string
    {
        if(!Container::Settings()->get("application.invitation.enabled")) {
            Response::redirect("/External/Login");
        }

        $this->_triad->View->setName("InvitationCode");

        return $this->_triad->View->render();
    }

    /**
     * Renders and returns the Login page.
     *
     * @return string Login page content.
     */
    public function Login() : string
    {
        $this->_triad->View->setName("Login");

        return $this->_triad->View->render();
    }

    /**
     * Renders and returns the Register page.
     *
     * @return string Register page content.
     */
    public function Register() : string
    {
        $this->_triad->View->setName("Register");

        return $this->_triad->View->render();
    }

    /**
     * Performs the validation of an invitation code.
     *
     * @param string $code Invitation code to verify.
     */
    public function verifyInvitationCode($code)
    {
        if(
            Container::Settings()->get("application.invitation.enabled") ||
            $code != Container::Settings()->get("application.invitation.code")
        ) {
            /*$query = new QueryBuilder(Container::Database());
            $query->select()
                  ->from("invitation_codes")
                  ->where([
                      "code" => $code
                  ])
                  ->execute();*/

            if(true) {
                Results::flash("code_doesnt_exist", [
                    "result"  => "success",
                    "message" => t("The invitation code does not exists!")
                ]);

                Response::redirect("/External/InvitationCode");
            }
        }

        Container::Session()->invitation = [
            "verified" => true,
            "code"     => $code
        ];
        Results::flash("code_verified", [
            "result"  => "success",
            "message" => t("The invitation code has been successfully validated!")
        ]);

        Response::redirect("/External/Login");
    }

    /**
     * Performs a login attempt.
     *
     * @param string $username Username.
     * @param string $password Password.
     *
     * @return string Response content
     */
    public function doLogin($username, $password) : string
    {
        $Database = Container::Database();
        $Session  = Container::Session();
        $Logger   = Container::Logger();

        $session_id = Str::random(32, "0123456789abcdef");

        if(!$this->_checkInfo($username, $password)) {
            return $this->Login();
        }

        // $username = Security::cleanXSS($username);
        // $password = Security::hash($password);

        $query = new QueryBuilder($Database);
        $query->select()
              ->from("users")
              ->where([
                  "AND" => [
                      "name"     => $username,
                      "password" => $password
                  ]
              ]);

        $id = $Database->execute($query, false);
        if(!is_numeric($id["id"])) {
            Results::addFlash("invalid_username_password", [
                "result"  => "danger",
                "message" => t("Username/password does not exists")
            ]);

            return $this->Login();
        }

        $query->clear();

        $query->update("accounts")
              ->set([
                    "session_id" => $session_id
                ])
              ->where([
                    "id" => $id["last_login_accounts_id"]
                ]);
        $Database->execute($query);

        $Session->id = $session_id;

        Response::redirect("/Internal/Start");
    }

    /**
     * Performs a register attempt.
     *
     * @param string $username Username.
     * @param string $password Password.
     * @param string $email    Email.
     * @param string $tac      Terms and Conditions.
     *
     * @return string Response content.
     */
    public function doRegister(string $username, string $password, string $email, string $tac = "1") : String
    {
        $Session  = Container::Session();
        $Database = Container::Database();

        $session_id = Str::random(32, "0123456789abcdef");


        if(!$this->_checkInfo($username, $password, $email, $tac)) {
            return $this->Register();
        }

        // $username = Security::cleanXSS($username);
        // $password = Security::hash($password);
        // $email    = Security::cleanXSS($email);

        //Check username and email is unique in db
        $query = new QueryBuilder($Database);
        $query->select("id")
              ->from("users")
              ->where([
                  "email" => $email
              ]);

        $email_exists = $Database->execute($query, false);
        if(!empty($email_exists)) {
            Results::flash("email_already_exists", [
                "result"  => "danger",
                "message" => t("Given email already exists, please, login or try with another email")
            ]);

            return $this->Register();
        }

        $username_exists = $Database->execute("SELECT * FROM `users` LEFT JOIN `accounts` USING(`id`) WHERE `users`.`name`='{$username}' OR `accounts`.`name`='{$username}';", false);
        if(!empty($username_exists)) {
            Results::flash("username_already_exists", [
                "result"  => "danger",
                "message" => t("Given username already exists, please, login or try with another username")
            ]);

            return $this->Register();
        }

        if(!$this->_registerUser($username, $password, $email, $session_id)) {
            Results::flash("register_error", [
                "result"  => "danger",
                "message" => t("There was an error registering!")
            ]);

            return $this->Register();
        }

        //Register successful
        $Session->id = $session_id;
        Response::redirect("/Internal/CompanyChoose");
    }

    /**
     * Checks that user provided info is valid for login/registering.
     *
     * @param string $username User name.
     * @param string $password Password.
     * @param string $email    Email.
     * @param string $tac      User accepts terms and conditions.
     *
     * @return bool `true` if user can login/register, `false` if not.
     */
    private function _checkInfo($username, $password, $email = null, $tac = null) : bool
    {
        $Settings = Container::Settings();

        $validator = new Validator();

        $validator->add("username", $username)
                  ->addRule("required", t("Please, enter your username"))
                  ->addRule("min_length", $Settings->get("application.forms.username.min_length"), t("Your username can't be shorter than ". $Settings->get("application.forms.username.min_length") ." chars"))
                  ->addRule("max_length", $Settings->get("application.forms.username.max_length"), t("Your username can't be longer than ". $Settings->get("application.forms.username.max_length") ." chars"))
                  ->addRule("not_contains_chars", $Settings->get("application.forms.username.not_allowed_chars"), t("Your username can't contain special chars"));

        $validator->add("password", $password)
                  ->addRule("required", t("Please, enter your password"))
                  ->addRule("min_length", $Settings->get("application.forms.password.min_length"), t("Your password can't be shorter than ". $Settings->get("application.forms.password.min_length") ." chars"))
                  ->addRule("max_length", $Settings->get("application.forms.password.max_length"), t("Your password can't be longer than ". $Settings->get("application.forms.password.max_length") ." chars"))
                  ->addRule("not_contains_chars", $Settings->get("application.forms.password.not_allowed_chars"), t("Your password can't contain special chars"));

        /*if($email != null) {
            $validator->add("email", $email)
                      ->addRule("required", t("Please, enter your email"))
                      ->addRule("is_email", t("Please, enter a valid email"));
        }

        if($tac != null) {
            $validator->add("tac", $email)
                      ->addRule("as_boolean", t("Please, accept the Terms and Conditions"));
        }*/

        if($validator->validate()) {
            return true;
        }

        foreach($validator->getErrors() as $error) {
            Results::flash("validator_error", [
                "result"  => "danger",
                "message" => $error
            ]);
        }

        return false;
    }

    /**
     * Registers a new user in the database.
     *
     * @param string $username   User name.
     * @param string $password   Password.
     * @param string $email      Email.
     * @param string $session_id Session ID.
     *
     * @return bool Whether the register succeeded or not.
     */
    private function _registerUser(string $username, string $password, string $email, string $session_id) : bool
    {
        $Database = Container::Database();
        $Logger   = Container::Logger();
        $Settings = Container::Settings();
        $query    = new QueryBuilder($Database);

        //First insert `users` row
        $query->insert("users")
              ->values([
                    "register_ip"   => IP,
                    "last_login_ip" => IP,
                    "session_id"    => Str::random(32, "0123456789abcdef"),
                    "name"          => $username,
                    "password"      => $password,
                    "email"         => $email
                ]);
        $insert_users = $Database->insert($query);

        if(empty($insert_users)) {
            $Logger->debug("Couldn't insert `users`: ". $Database->getError());
            $Logger->debug("Query executed: ". $Database->lastQuery);

            return false;
        }

        $query->clear();

        //Now insert `accounts` row
        $query->insert("accounts")
              ->values([
                    "users_id"          => $insert_users,
                    "register_ip"       => IP,
                    "register_users_id" => $insert_users,
                    "session_id"        => $session_id,
                    "name"              => $username,
                    "uridium"           => $Settings->get("application.register.uridium"),
                    "credits"           => $Settings->get("application.register.credits"),
                    "experience"        => $Settings->get("application.register.experience"),
                    "levels_id"         => $Settings->get("application.register.level"),
                    "honor"             => $Settings->get("application.register.honor"),
                    "jackpot"           => $Settings->get("application.register.jackpot"),
                    "ranks_id"          => $Settings->get("application.register.rank"),
                    "is_premium"        => $Settings->get("application.register.premium"),
                ]);
        $insert_accounts = $Database->insert($query);

        if(empty($insert_accounts)) {
            $Logger->debug("Couldn't insert `accounts`: ". $Database->getError());
            $Logger->debug("Query executed: ". $Database->lastQuery);

            return false;
        }

        $query->clear();

        //Update `users` row and set account's ID
        $query->update("users")
              ->set([
                    "register_accounts_id"   => $insert_accounts,
                    "last_login_accounts_id" => $insert_accounts
                ])
              ->where([
                    "id" => $insert_users
                ]);
        $update_users = $Database->execute($query);

        $query->clear();

        //Turn for Equipment
        //First thing is the hangar
        //Once hangar is done we will give the user a ship
        //Next thing are items
        //Finally the configurations
        $query->insert("accounts_equipment_hangars")
              ->values([
                  "accounts_id"       => $insert_accounts,
                  "(JSON)resources"   => $Settings->get("application.register.resources")
              ]);

        $insert_accounts_equipment_hangars = $Database->insert($query);

        if(empty($insert_accounts_equipment_hangars)) {
            $Logger->debug("Couldn't insert `accounts_equipment_hangars`: ". $Database->getError());
            $Logger->debug("Query executed: ". $Database->lastQuery);

            return false;
        }

        $query->clear();

        $query->insert("accounts_equipment_ships")
              ->values([
                  "accounts_id"                   => $insert_accounts,
                  "ships_id"                      => $Settings->get("application.register.ship.id"),
                  "ships_designs_id"              => $Settings->get("application.register.ship.designs_id"),
                  "gfx"                           => $Settings->get("application.register.ship.gfx"),
                  "maps_id"                       => $Settings->get("application.register.ship.maps_id"),
                  "(JSON)position"                => $Settings->get("application.register.ship.position"),
                  "health"                        => $Settings->get("application.register.ship.health"),
                  "nanohull"                      => $Settings->get("application.register.ship.nanohull"),
                  "shield"                        => $Settings->get("application.register.ship.shield"),
              ]);

        $insert_accounts_equipment_ships = $Database->insert($query);

        if(empty($insert_accounts_equipment_ships)) {
            $Logger->debug("Couldn't insert `accounts_equipment_ships`: ". $Database->getError());
            $Logger->debug("Query executed: ". $Database->lastQuery);

            return false;
        }

        $query->clear();

        //Update `accounts` row and set hangar's ID
        $query->update("accounts")
              ->set([
                    "accounts_equipment_hangars_id" => $insert_accounts_equipment_hangars
                ])
              ->where([
                    "id" => $insert_accounts
                ]);
        $update_accounts = $Database->execute($query);

        $query->clear();

        //Update `accounts_equipment_hangars` row and set ship's ID
        $query->update("accounts_equipment_hangars")
              ->set([
                  "accounts_equipment_ships_id" => $insert_accounts_equipment_ships
              ])
              ->where([
                  "id" => $insert_accounts_equipment_hangars
              ]);

        $update_aaccounts_equipment_hangars = $Database->execute($query);

        $query->clear();

        $insert_items = [];
        for($i = 0; $i < count($Settings->get("application.register.items")); $i++) {
            $item = $Settings->get("application.register.items")[$i];

            $query->insert("accounts_equipment_items")
                  ->values([
                      "items_id"  => $item["id"],
                      "levels_id" => $item["levels_id"],
                      "amount"    => $item["amount"]
                  ]);

            $insert_items[] = $Database->insert($query);

            if(empty($insert_items[$i])) {
                $Logger->debug("Couldn't insert `accounts_equipment_items`: ". $Database->getError());
                $Logger->debug("Query executed: ". $Database->lastQuery);
                //There's no need to make registration unsuccessfull just because we couldn't add 1 item
            }

            $query->clear();
        }

        //Lets move the configuration queries to the configuration file so it's easier to edit in the future
        $Settings->get("application.register.configurations")($insert_items, $insert_accounts_equipment_ships);

        //Turn for the galaxy gates
        for($i = 0; $i < count($Settings->get("application.register.galaxygates")); $i++) {
            $gg = $Settings->get("application.register.galaxygates")[$i];

            $query->insert("accounts_galaxygates")
                  ->values([
                      "galaxygates_id" => $gg["id"],
                      "accounts_id"    => $insert_accounts,
                      "parts"          => $gg["parts"],
                      "lives"          => $gg["lives"],
                      "amount"         => $gg["amount"]
                  ]);

            $insert_gg = $Database->insert($query);

            if(empty($insert_gg)) {
                $Logger->debug("Couldn't insert `accounts_galaxygate`: ". $Database->getError());
                $Logger->debug("Query executed: ". $Database->lastQuery);
                //There's no need to make registration unsuccessfull just because we couldn't add 1 galaxygate
            }

            $query->clear();
        }

        //Now time for the less important things: The profile and the welcome message

        $query->insert("accounts_profiles")
              ->values([
                  "accounts_id" => $insert_accounts,
                  "avatar"      => $Settings->get("application.register.avatar"),
                  "status"      => $Settings->get("application.register.status")
              ]);

        $insert_profile = $Database->insert($query);

        if(empty($insert_profile)) {
            $Logger->debug("Couldn't insert `accounts_profiles`: ". $Database->getError());
            $Logger->debug("Query executed: ". $Database->lastQuery);
        }

        $query->clear();

        foreach($Settings->get("application.register.messages") as $message) {
            $query->insert("accounts_messages")
                  ->values([
                      "from_accounts_id" => $message["author_id"],
                      "from_status"      => $message["author_status"],
                      "to_accounts_id"   => $insert_accounts,
                      "to_status"        => $message["status"],
                      "title"            => $message["title"],
                      "text"             => $message["text"]
                  ]);

            $insert_message = $Database->insert($query);

            if(empty($insert_message)) {
                $Logger->debug("Couldn't insert `accounts_messages`: ". $Ddatabase->getError());
                $Logger->debug("Query executed: ". $Database->lastQuery);
            }

            $query->clear();
        }

        return true;
    }
}
