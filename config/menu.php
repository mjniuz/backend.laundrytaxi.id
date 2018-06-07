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
        "title" => "Order",
        "link"  => "#",
        "icon"  => "vcard-o",
        "as"    => "order.",
        "sub"   => [
            [
                "title" => "List All",
                "link"  => "order",
                "icon"  => "circle-o",
                "as"    => "",
            ],
            [
                "title" => "Create",
                "link"  => "order/create",
                "icon"  => "pencil",
                "as"    => "",
            ]
        ]
    ],[
        "title" => "User",
        "link"  => "#",
        "icon"  => "users",
        "as"    => "user.",
        "sub"   => [
            [
                "title" => "List All",
                "link"  => "user",
                "icon"  => "circle-o",
                "as"    => "",
            ],
            [
                "title" => "Create",
                "link"  => "user/create",
                "icon"  => "pencil",
                "as"    => "",
            ]
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
    ]
];

return $menu;