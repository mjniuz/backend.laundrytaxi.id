<?php

$menu   = [
    [
        "title" => "Dashboard",
        "link"  => "index",
        "icon"  => "dashboard",
        "as"    => "dashboard.",
        "sub"   => [
        ]
    ],[
        "title" => "Administrator",
        "link"  => "#",
        "icon"  => "vcard-o",
        "as"    => "administrator.",
        "sub"   => [
            [
                "title" => "List All",
                "link"  => "administrator",
                "icon"  => "circle-o",
                "as"    => "",
            ],
            [
                "title" => "Roles",
                "link"  => "administrator/role",
                "icon"  => "unlock",
                "as"    => "role.",
            ],
            [
                "title" => "Permission",
                "link"  => "administrator/permission",
                "icon"  => "key",
                "as"    => "permission.",
            ],
            [
                "title" => "Group",
                "link"  => "administrator/group",
                "icon"  => "users",
                "as"    => "group.",
            ]
        ]
    ],
];

return $menu;