<?php
namespace App\AdminUsers;

class ItemsHelper {

    private $items, $role;

    public function __construct($items, $role = []) {
        $this->items = $items;
        $this->role  = !empty($role) ? array_flip($role) : [];
    }

    public function itemArray() {
        $result = array();
        foreach($this->items as $item) {
            if ($item->parent_id == null) {
                $child   = $this->itemWithChildren($item);
                $result[$item->name]  = !empty($child) ? $child : true;
            }
        }
        return $result;
    }

    private function childrenOf($item) {
        $result = array();
        foreach($this->items as $i) {
            if ($i->parent_id == $item->id) {
                $result[] = $i;
            }
        }
        return $result;
    }

    private function itemWithChildren($item) {
        $result = array();
        $children = $this->childrenOf($item);
        foreach ($children as $child) {
            $newChild   = $this->itemWithChildren($child);
            $result[$child->name]  = !empty($newChild) ? $newChild : true;
        }
        return $result;
    }

    /*
     * To ul li
     */

    public function itemArrayList($isRole = false) {
        $result = "";
        foreach($this->items as $item) {
            if ($item->parent_id == 0) {
                $child  = $this->itemWithChildrenList($item, $isRole);
                $result .= "<ul class='list-group'><li>∙ ";
                if($isRole){
                    $checked    = array_key_exists($item->id, $this->role) ? "checked='checked'" : "";
                    $result .= "<input type='checkbox' name='permission[]' value='" . $item->id . "' " . $checked . "/> ";
                }

                $result .= ($item->name == "*") ? $item->name . " All" : $item->name;

                if(!$isRole){
                    $result .= " <a href='" . url('backend/administrator/permission/edit/' . $item->id) . "' class='btn btn-xs btn-primary'>Edit</a>";
                }

                if(!empty($child)){
                    $result .= "<ul>";
                    $result .= $child;
                    $result .= "</ul>";
                }
                $result .= "</li></ul>";
            }
        }

        return $result;
    }

    private function childrenOfList($item) {
        $result = [];
        foreach($this->items as $i) {
            if ($i->parent_id == $item->id) {
                $result[] = $i;
            }
        }

        return $result;
    }

    private function itemWithChildrenList($item, $isRole = false) {
        $result = "";
        $children = $this->childrenOfList($item);
        foreach ($children as $child) {
            $result .= "<li>∙ ";
            if($isRole){
                $checked    = array_key_exists($child->id, $this->role) ? "checked='checked'" : "";
                $result .= "<input type='checkbox' name='permission[]' value='" . $child->id . "' " . $checked . "/> ";
            }

            $result .= ($child->name == "*") ? $child->name . " All" : $child->name;

            if(!$isRole) {
                $result .= " <a href='" . url('backend/administrator/permission/edit/' . $child->id) . "' class='btn btn-xs btn-primary'>Edit</a>";
            }

            $childNew = $this->itemWithChildrenList($child, $isRole);
            if(!empty($childNew)){
                $result .= "<ul>";
                $result .= $childNew;
                $result .= "</ul>";
            }

            $result .= "</li>";
        }
        return $result;
    }

    /*
     * to default multi child array
     */
    public function itemMultiArray() {
        $result = array();
        foreach($this->items as $item) {
            if ($item->parent_id == null) {
                $child      = $this->multiItemWithChildren($item);
                $data       = [
                    "id"        => $item->id,
                    "text"      => $item->name,
                    "children"  => !empty($child) ? $child : null
                ];
                if(empty($child)){
                    unset($data["children"]);
                }

                $result[]  = $data;
            }
        }
        return $result;
    }

    private function multiChildrenOf($item) {
        $result = array();
        foreach($this->items as $i) {
            if ($i->parent_id == $item->id) {
                $result[] = $i;
            }
        }
        return $result;
    }

    private function multiItemWithChildren($item) {
        $result = array();
        $children = $this->multiChildrenOf($item);
        foreach ($children as $child) {
            $newChild   = $this->multiItemWithChildren($child);
            $data       = [
                "id"        => $child->id,
                "text"      => $child->name,
                "children"     => !empty($newChild) ? $newChild : null
            ];

            if(empty($newChild)){
                unset($data['children']);
            }
            $result[]   = $data;
        }
        return $result;
    }
}