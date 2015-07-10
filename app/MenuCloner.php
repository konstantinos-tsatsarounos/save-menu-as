<?php

/**
 * Created by Konstantinos Tsatsarounos<konstantinos.tsatsarounos@gmail.com>
 * Date: 10/7/2015
 * File: MenuCloner.php
 * Description: A user can use this class to clone a menu from the id
 */
class MenuCloner
{
    private $menu_items = NULL;
    private $counter = NULL;
    private $createdMenu = NULL;

    public function readMenu($menu_id){
        $this->menu_items = wp_get_nav_menu_items(  $menu_id );
    }

    public function createMenu($menu_name){
        $this->createdMenu = $new_menu = wp_create_nav_menu($menu_name);

        if( is_numeric( $new_menu ) ){
            foreach($this->menu_items as $menu_item){
                $this->setMenuItem($menu_item);
                $this->counter++;
            }
        }
        return json_encode( $this->createdMenu );
    }

    private function setMenuItem($menu_item){
        $args = array(
            'menu-item-db-id'       => $menu_item->db_id,
            'menu-item-object-id'   => $menu_item->object_id,
            'menu-item-object'      => $menu_item->object,
            'menu-item-position'    => $this->counter,
            'menu-item-type'        => $menu_item->type,
            'menu-item-title'       => $menu_item->title,
            'menu-item-url'         => $menu_item->url,
            'menu-item-description' => $menu_item->description,
            'menu-item-attr-title'  => $menu_item->attr_title,
            'menu-item-target'      => $menu_item->target,
            'menu-item-classes'     => implode( ' ', $menu_item->classes ),
            'menu-item-xfn'         => $menu_item->xfn,
            'menu-item-status'      => $menu_item->post_status
        );

        $parent_id = wp_update_nav_menu_item( $this->createdMenu, 0, $args );

        $rel[$menu_item->db_id] = $parent_id;

        if ( $menu_item->menu_item_parent ) {
            $args['menu-item-parent-id'] = $rel[$menu_item->menu_item_parent];
            $parent_id = wp_update_nav_menu_item($this->createdMenu, $parent_id, $args );
        }

        return !!$parent_id;
    }
}