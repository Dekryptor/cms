<?php
/**
 * Alexya Framework - The intelligent Loli Framework.
 *
 * Application specific configuration.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */

return [
    /**
     * Whether we're under debug mode or not
     */
    "debug" => true,

    /**
     * Invitation code settings.
     */
    "invitation" => [
        /**
         * Whether invitation code system is enabled or not.
         */
        "enabled" => false,

        /**
         * Global invitation code.
         */
        "code" => "TotallyAnInvitationCodeThatCantBeGuessedByAnyone"
    ],

    /**
     * Global view variables.
     */
    "view_vars" => [
        "ip"  => "127.0.0.1",
        "URL" => "http://localhost/"
    ],

    /**
     * Form rules.
     */
    "forms" => [
        /**
         * Rules for username input.
         */
        "username" => [
            /**
             * Min username length.
             */
            "min_length" => 4,

            /**
             * Max username length.
             */
            "max_length" => 255,

            /**
             * Not allowed chars in username.
             */
            "not_allowed_chars" => " "
        ],

        /**
         * Rules for password input.
         */
        "password" => [
            /**
             * Min password length.
             */
            "min_length" => 4,

            /**
             * Max password length.
             */
            "max_length" => 255,

            /**
             * Not allowed chars in password.
             */
             "not_allowed_chars" => ""
        ]
    ],

    /**
     * The default things to insert in the Database
     * when the user registers.
     */
    "register" => [
        /////////////////////////
        // Begin account stats //
        /////////////////////////
        "uridium"    => 2000,
        "credits"    => 20000,
        "experience" => 0,
        "level"      => 1,
        "honor"      => 0,
        "jackpot"    => 0,
        "rank"       => 1,
        "premium"    => 0,
        ///////////////////////
        // End account stats //
        ///////////////////////

        /**
         * Default resources that are given once user registers.
         */
        "resources" => [
            0, // Prometium
            0, // Endurium
            0, // Terbium
            0, // Xenomit
            0, // Prometid
            0, // Duranium
            0, // Promerium
            0, // Seprom
            0, // Palladium
        ],

        /**
         * User's ship.
         */
        "ship" => [
            "id"         => 1, // Phoenix
            "designs_id" => 0, // No design
            "gfx"        => 1, // Phoenix graphic
            "maps_id"    => 1, // 1-1 (will be changed once user chooses a company)
            "position"   => [1000, 1000], // X, Y
            "health"     => 4000, // Default Phoenix HP
            "nanohull"   => 0, // No extra life :(
            "shield"     => 0, // No shield :(
        ],

        /**
         * Items to give.
         */
        "items" => [
            // No items for now (I need to add them to database first xD).
        ],

        /**
         * Configurations to add.
         *
         * @param array $items   Array with items id.
         * @param int   $ship_id Account's ship id.
         */
        "configurations" => function($items, $ship_id) {
            $lasers     = [];
            $hellstorms = [];
            $generators = [];
            $extras     = [];

            foreach($items as $item_id) {
                $item = \Application\ORM\Accounts\Equipment\Item::find($item);

                if($item->Item->category == "laser") {
                    $lasers[] = $item_id;
                } else if($item->Item->category == "hellstorms") {
                    $hellstorms[] = $item_id;
                } else if($item->Item->category == "generators") {
                    $generators[] = $item_id;
                } else if($item->Item->category == "extras") {
                    $extras[] = $item_id;
                }
            }

            $config = \Alexya\Database\ORM\Model::create("accounts_equipment_configurations");

            $config->accounts_equipment_ships_id = $ship_id;
            $config->configuration               = 1;
            $config->lasers                      = json_encode($lasers);
            $config->hellstorms                 = json_encode($hellstorms);
            $config->generators                 = json_encode($generators);
            $config->extras                     = json_encode($extras);

            $config->save();

            $config = \Alexya\Database\ORM\Model::create("accounts_equipment_configurations");

            $config->accounts_equipment_ships_id = $ship_id;
            $config->configuration               = 2;
            $config->lasers                      = json_encode($lasers);
            $config->hellstorms                 = json_encode($hellstorms);
            $config->generators                 = json_encode($generators);
            $config->extras                     = json_encode($extras);

            $config->save();
        },

        /**
         * Galaxy gates.
         */
        "galaxygates" => [
            [
                "id"     => 1, // Alpha.
                "parts"  => 0, // Starting parts.
                "lives"  => 0, // No lifes because isn't build yet.
                "amount" => 0, // No gates completed.
            ],
            [
                "id"     => 2, // Beta.
                "parts"  => 0, // Starting parts.
                "lives"  => 0, // No lifes because isn't build yet.
                "amount" => 0, // No gates completed.
            ],
            [
                "id"     => 3, // Gamma.
                "parts"  => 0, // Starting parts.
                "lives"  => 0, // No lifes because isn't build yet.
                "amount" => 0, // No gates completed.
            ],
            [
                "id"     => 4, // Delta.
                "parts"  => 0, // Starting parts.
                "lives"  => 0, // No lifes because isn't build yet.
                "amount" => 0, // No gates completed.
            ]
        ],

        ////////////////////
        // Beging profile //
        ////////////////////
        "avatar" => "default.jpg",
        "status" => "",
        /////////////////
        // End profile //
        /////////////////

        /**
         * Welcome messages.
         */
        "messages" => [
            [
                "author_id"     => 0, //System message.
                "author_status" => 0, //Unread.
                "status"        => 0, //User's status.
                "title"         => "Welcome to BlackEye", //Message title.
                "text"          => "Greetings Space Pilot,

                Your BlackEye journey will lead you to far-off galaxies fraught with danger and mystery. Your first rule of thumb is not to panic!

                If you can\'t find any answers to your questions contact Support.

                Godspeed, captain!"
            ]
        ]
    ]
];
