
<?php
$gnrl = $gnl = new common\components\GeneralComponent();
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!--        <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?php echo $gnrl->image_not_found_profile('profile_photo', Yii::$app->user->identity->profile_photo); ?>" class="img-circle" alt="User Image"/>
                    </div>
                    <div class="pull-left info" >
                        <p style="text-align: center; margin-top: 12px;"><?= Yii::$app->user->identity->first_name . ' ' . Yii::$app->user->identity->last_name ?></p>
                    </div>
                </div>-->


        <?php
if(\Yii::$app->user->identity->role == "superAdmin"){
    
    


        $menu_query = "SELECT * FROM dynamic_menu WHERE status = 1 AND type != 0  ORDER BY sort";                
        $menu_array = Yii::$app->db->createCommand($menu_query)->queryAll();
        $menuItems = [];
        foreach ($menu_array as $key => $value) {
            if($value['type'] == 1){
                if ($value['index_array'] != '') {
                    $index_array = explode(",", $value['index_array']); //make array
                } else {
                    $index_array = [];
                }
                    $menuItems[] = 
                    ['label' => $value['label'], 'icon' => $value['icon'], 'url' => [$value['url']], 
                        'active' => (in_array(\Yii::$app->controller->action->id, $index_array) && \Yii::$app->controller->id == $gnl->get_controller_name($value['controller_id'])),
                    ];
            }
            else if($value['type'] == 2){
                $item_query = "SELECT * FROM dynamic_menu WHERE status = 1 AND type = 0  AND menu_id = ".$value['id']." ORDER BY sort";                
                $menu_item_array = Yii::$app->db->createCommand($item_query)->queryAll();
                $itemsArray = [];
                foreach ($menu_item_array as $key => $item_value) {
            
                if ($item_value['index_array'] != '') {
                    $index_item_array = explode(",", $item_value['index_array']); //make array
                } else {
                    $index_item_array = [];
                }
                    $itemsArray[] = 
                    ['label' => $item_value['label'], 'icon' => $item_value['icon'], 'url' => [$item_value['url']], 
                     'active' => (in_array(\Yii::$app->controller->action->id, $index_item_array) && \Yii::$app->controller->id == $gnl->get_controller_name($item_value['controller_id'])),
                    ];
                }
                
                $menuItems[] = 
                ['label' => $value['label'],
                            'icon' => $value['icon'],
                            'url' => [$value['url']],
                            'items' =>   $itemsArray,
                            'visible' => $gnl->left_sidebar_for_admin($value['id']),
                ];
            }
            
                
                }
echo dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => $menuItems,
                ]
        );    
    
    
         /*echo dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => [
                        ['label' => 'Dashboard', 'icon' => 'fa fa-dashboard', 'url' => ['site/index']],
//                        ['label' => 'Access Control',
//                            'icon' => 'fa fa-users',
//                            'url' => '#',
//                            'items' => [
//                                ['label' => 'Roles', 'icon' => 'fa fa-user', 'url' => ['roles/index'],
//                                    'active' => (in_array(\Yii::$app->controller->action->id, ['create', 'index', 'update', 'role-allocation']) && \Yii::$app->controller->id == "roles")],
//                            ]],
                        ['label' => 'User Management',
                            'icon' => 'fa fa-users',
                            'url' => '#',
                            'items' => [
                                ['label' => 'Student Management', 'icon' => 'fa fa-graduation-cap ', 'url' => ['user/student'],
                                'active' => (in_array(\Yii::$app->controller->action->id, ['student', 'update-student', 'change-password-student', 'create-student']) &&
                                 \Yii::$app->controller->id == "user")],
                                ['label' => 'Tutor Management', 'icon' => 'fa fa-group ', 'url' => ['user/tutor'],
                                 'active' => (in_array(\Yii::$app->controller->action->id, ['tutor', 'update-tutor', 'change-password-tutor', 'create-tutor']) &&
                                  \Yii::$app->controller->id == "user")],
                                ['label' => 'Sub-Admin Management', 'icon' => 'fa fa-user ', 'url' => ['user/sub-admin'],
                                 'active' => (in_array(\Yii::$app->controller->action->id, ['sub-admin', 'update', 'change-password-sub-admin', 'create-sub-admin']) &&
                                 \Yii::$app->controller->id == "user")],
                                ],
                        ],
                        ['label' => 'Master Management',
                            'icon' => 'fa fa-tag',
                            'url' => '#',
                            'items' => [
                                ['label' => 'Qualifications', 'icon' => 'fa fa-angle-right', 'url' => ['qualification/index'],
                                    'active' => (in_array(\Yii::$app->controller->action->id, ['index', 'create', 'update']) &&
                                    \Yii::$app->controller->id == "qualification")],
                                ['label' => 'Subjects', 'icon' => 'fa fa-angle-right', 'url' => ['subject/index'],
                                    'active' => (in_array(\Yii::$app->controller->action->id, ['index', 'create', 'update']) &&
                                    \Yii::$app->controller->id == "subject")],
                                /*['label' => 'Expertise', 'icon' => 'fa fa-angle-right', 'url' => ['expertise/index'],
                                    'active' => (in_array(\Yii::$app->controller->action->id, ['index', 'create', 'update']) &&
                                    \Yii::$app->controller->id == "expertise")],
                                 * end of comment
                            ]
                        ],
                        ['label' => 'CMS Management',
                            'icon' => 'fa fa-file-text',
                            'url' => '#',
                            'items' => [
                                ['label' => 'CMS', 'icon' => 'fa fa-angle-right','url' => ['cms/index'],
                                    'active' => (in_array(\Yii::$app->controller->action->id, ['index', 'update']) && \Yii::$app->controller->id == "cms")],
                                ['label' => 'Careers','icon' => 'fa fa-angle-right','url' => ['career/index'],
                                    'active' => (in_array(\Yii::$app->controller->action->id, ['index', 'update']) && \Yii::$app->controller->id == "career")],
                                ['label' => 'FAQs','icon' => 'fa fa-angle-right','url' => ['faq/index'],
                                    'active' => (in_array(\Yii::$app->controller->action->id, ['index', 'update']) && \Yii::$app->controller->id == "faq")],
                            ],
                        ],
//                        ['label' => 'Bulk Mail', 'icon' => 'fa fa-envelope', 'url' => ['bulk-mail/create']],
                        ['label' => 'Settings', 'icon' => 'fa fa-gears', 'url' => ['setting/update?id=' . \common\components\GeneralComponent::encrypt($setting_id)]],
                    /* ['label' => 'Commissions Management', 'icon' => 'fa fa-quote-left', 'url' => ['commissions/index'], 
                      'active' => (in_array(\Yii::$app->controller->action->id,
                      []) &&
                      \Yii::$app->controller->id == "user")],
                      ['label' => 'Category Management', 'icon' => 'fa fa-tags', 'url' => ['category/index'],
                      'active' => (in_array(\Yii::$app->controller->action->id,
                      []) &&
                      \Yii::$app->controller->id == "user")],
                      ['label' => 'Content and CMS Management', 'icon' => 'fa fa-bolt', 'url' => ['cms/index'],
                      'active' => (in_array(\Yii::$app->controller->action->id,
                      []) &&
                      \Yii::$app->controller->id == "user")],
                      ['label' => 'Invoice Management', 'icon' => 'fa fa-print', 'url' => ['invoice/index'],
                      'active' => (in_array(\Yii::$app->controller->action->id,
                      []) &&
                      \Yii::$app->controller->id == "user")],
                      ['label' => 'Reviews Management', 'icon' => 'fa fa-star', 'url' => ['reviews/index'],
                      'active' => (in_array(\Yii::$app->controller->action->id,
                      []) &&
                      \Yii::$app->controller->id == "user")],
                      ['label' => 'Questions management', 'icon' => 'fa fa-question-circle', 'url' => ['questions/index'],
                      'active' => (in_array(\Yii::$app->controller->action->id,
                      []) &&
                      \Yii::$app->controller->id == "user")],
                      ['label' => 'Reconciliation', 'icon' => 'fa fa-gears', 'url' => ['reconciliation/index'],
                      'active' => (in_array(\Yii::$app->controller->action->id,
                      []) &&
                      \Yii::$app->controller->id == "user")],

                     * 
                     *end of comment
                    ],
                ]
        );  
                     * 
                     */ 
                            
}
 else if(\Yii::$app->user->identity->role == "subAdmin"){
     
        $setting = new \common\models\Setting();
        $model = $setting->findAll(array('status' => 1));
        $data_array = $model[0]->attributes;
        $setting_id = $data_array['setting_id'];   
        $menu_query = "SELECT * FROM dynamic_menu WHERE status = 1 AND type != 0 AND role_id = '3' ORDER BY sort";                
        $menu_array = Yii::$app->db->createCommand($menu_query)->queryAll();
        $menuItems = [];
        foreach ($menu_array as $key => $value) {
            if($value['type'] == 1){
                if ($value['index_array'] != '') {
                    $index_array = explode(",", $value['index_array']); //make array
                } else {
                    $index_array = [];
                }
                    $menuItems[] = 
                    ['label' => $value['label'], 'icon' => $value['icon'], 'url' => [$value['url']], 
                        'active' => (in_array(\Yii::$app->controller->action->id, $index_array) && \Yii::$app->controller->id == $gnl->get_controller_name($value['controller_id'])),
                        'visible' => $gnl->left_sidebar_for_subadmin($value['key']),
                    ];
            }
            else if($value['type'] == 2){
                $item_query = "SELECT * FROM dynamic_menu WHERE status = 1 AND type = 0 AND role_id = '3' AND menu_id = ".$value['id']." ORDER BY sort";                
                $menu_item_array = Yii::$app->db->createCommand($item_query)->queryAll();
                $itemsArray = [];
                foreach ($menu_item_array as $key => $item_value) {
            
                if ($item_value['index_array'] != '') {
                    $index_item_array = explode(",", $item_value['index_array']); //make array
                } else {
                    $index_item_array = [];
                }
                    $itemsArray[] = 
                    ['label' => $item_value['label'], 'icon' => $item_value['icon'], 'url' => [$item_value['url']], 
                        'active' => (in_array(\Yii::$app->controller->action->id, $index_item_array) && \Yii::$app->controller->id == $gnl->get_controller_name($item_value['controller_id'])),
                        'visible' => $gnl->left_sidebar_for_subadmin($item_value['key']),
                    ];
                }
                
                $menuItems[] = 
                ['label' => $value['label'],
                            'icon' => $value['icon'],
                            'url' => [$value['url']],
                            'items' =>   $itemsArray,
                            'visible' => $gnl->left_sidebar_for_subadmin_header($value['id']),
                ];
            }
            
                
                }
echo dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => $menuItems,
                ]
        );                
}
        ?>

    </section>

</aside>
