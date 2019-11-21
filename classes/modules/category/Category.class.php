<?php
/*
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Модуль управления универсальными категориями
 *
 * @package application.modules.category
 * @since 2.0
 */
class PluginFend_ModuleCategory extends PluginFend__Inherits_ModuleCategory
{
    protected $aBehaviors = [
        'seo' => [
            'class' => 'PluginSeo_ModuleSeo_BehaviorModule',
            'target_type' => 'category_seo'
        ]
    ];
    
    public function GetWithParents(array $aCategories) {
        
        if(!$aCategories){
            return [[],[],[]];
        }
        
        $aCategoryRoot = $this->Category_GetCategoryItemsByFilter([
            'pid' => null,
            '#index-from' => 'id'
        ]); 
        
        $aCategoryRootId = array_keys($aCategoryRoot);
        
        if(!is_object(current($aCategories))){
            $aCategories = $this->Category_GetCategoryItemsByFilter([
                'id in' => $aCategories
            ]);
        }
        
        $aCategory = [];
        $aCategory1 = [];
        $aCategory2 = [];
        
        /*
         * Выбрать всех предков катеорий в отдельные места
         */
        foreach ($aCategories as $category) {
            if(!$parent = $category->getParent()){
                continue;
            }

            if(in_array($parent->getId(), $aCategoryRootId))
            {
                $aCategory[$parent->getId()]   = $parent; 
                $aCategory1[$category->getId()]  = $category;
                
            }elseif(in_array($parent->getPid(), $aCategoryRootId))
            {
                $aCategory[$parent->getPid()]   = $parent->getParent(); 
                $aCategory1[$parent->getId()]  = $parent;
                $aCategory2[$category->getId()]  = $category;
            }
        }
        
        return [$aCategory, $aCategory1, $aCategory2];
    }

}