<?php

namespace App\Traits;

use App\Repositories\SettingRepository;

if (!defined('ABSPATH')) {
    exit;
}

trait CanInteractWithSettings
{
    public function getSettings($args = []){
        $settings = [];

        foreach ($args as $key) {
            $settings[$key] = SettingRepository::getSetting($key);
        }
        return $settings;
    }

    public function addSettings($data){
        $flag = false;
        foreach ($data as $key => $value) {
           $flag = SettingRepository::addSetting($key, $value);
        }
        return $flag;
    }
    
    public function updateSettings($data){
        foreach ($data as $key => $value) {
            SettingRepository::updateSetting($key, $value);
        }
    }
    
    public function deleteSettings($id){
        
    }
    
    public function createSettings($data){
        
    }
    
    public function getAllSettings(){
        
    }
}