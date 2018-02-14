<?php

namespace luya\admin\aws;

use luya\admin\ngrest\base\ActiveWindow;

/**
 * User History Summary Active Window.
 *
 * File has been created with `aw/create` command. 
 */
class UserHistorySummaryActiveWindow extends ActiveWindow
{
    /**
     * @var string The name of the module where the ActiveWindow is located in order to finde the view path.
     */
    public $module = '@admin';

    /**
     * Default icon if not set in the ngrest model.
     *
     * @var string The icon name from goolges material icon set (https://material.io/icons/)
     */
    public function defaultIcon()
    {
        return 'pie_chart';    
    }

    /**
     * The default action which is going to be requested when clicking the ActiveWindow.
     * 
     * @return string The response string, render and displayed trough the angular ajax request.
     */
    public function index()
    {
        return $this->render('index', [
            'model' => $this->model,
        	'userLogins' => $this->model->getUserLogins()->limit(25)->all(),
        	'ngrestLogs' => $this->model->getNgrestLogs()->limit(25)->all(),
        ]);
    }

    public function callbackPie()
    {
    	return [
    	'tooltip' => ['trigger' => 'item', 'formatter' => '{b}: {c} entries'],
    	'legend' => ['orient' => 'vertical', 'x' => 'left', 'data' => ['Additions', 'Updates', 'Unknown']],
    	'series' => [
    		[
    			'name' => "Hits",
    			'type' => 'pie',
    			'radius' => ['50%', '70%'],
    			'avoidLabelOverlap' => false,
    			'labelLine' => ['normal' => ['show' => false]],
    			'label' => [
    				'normal' => ['show' => false, 'position' => 'center'],
    				'emphasis' => [
    					'show' => true,
    					'textStyle' => [
    						'fontSize' => '30',
    						'fontWeight' => 'bold'
    					]
    				]
    			],
    			'data' => [
    				['value' => $this->model->getNgrestLogs()->where(['is_insert' => true])->count(), 'name' => 'Additions'],
    				['value' => $this->model->getNgrestLogs()->where(['is_update' => true])->count(), 'name' => 'Updates'],
    				['value' => $this->model->getNgrestLogs()->where(['is_update' => false, 'is_insert' => false])->count(), 'name' => 'Unknown'],
    			]
    		]
    	]
    ];
    }
}