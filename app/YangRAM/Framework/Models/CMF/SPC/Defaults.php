<?php
namespace CM\SPC;
use System\NIDO\DataObject;

/**
 *	专用内容预设（SPC）字段集模型
 *  字段主要为如下28种类型（括号内为数据库中的原始类型）
 *  如有特殊需要，请自行拓展，但不保证每一个应用都能正确识别并渲染自定义类型
 *
 *  **  ip          IP地址（短文本）
 *  **  is          布尔值（微整数0-1/布尔值）
 *  **  int         整数（整数）
 *  **  url         网址（短文本）
 *  **  text        单行文本（短文本）
 *  **  tags        建议适配标签UI的长文本（长文本，使用分隔符分隔的文本）
 *  **  date        日期（日期）
 *  **  file        文件网址（短文本）
 *  **  rate        评级（微整数）
 *  **  time        时间（时间）
 *  **  week        一个星期内的某一天（微整数0-6）
 *  **  color       颜色（6-8字节文本，即显示为十六进制RGB）
 *  **  email       邮件地址（短文本）
 *  **  files       文件列表（长文本，使用分隔符分隔的文本）
 *  **  month       月份（微整数0-11）
 *  **  radio       排列单选（短文本/整数）
 *  **  stamp       时间戳（时间戳/数字）
 *  **  editor      建议适配编辑器的长文本（长文本）
 *  **  hidden      隐藏（短文本）
 *  **  number      数字（浮点数）
 *  **  imgtext     base64格式图片（长文本）
 *  **  options     弹出单选（短文本/整数）
 *  **  percent     百分比（微整数0-100）
 *  **  checkbox    多选框（长文本，使用分隔符分隔的文本）
 *  **  datetime    日期时间（日期时间）
 *  **  longtext    长文本（长文本）
 *  **  textarea    多行文本（短文本）
 *  **  uploader    建议适配上传控件的文件网址（短文本）
 *
 */
final class Defaults extends DataObject {
    protected static
	$ca_path = PATH_DAT_CNT.'defaults/',
    $defaults = [
		'CAT_ID' => [
            'type'  =>  'int',
            'value' =>  0
        ],
		'TITLE' => [
            'type'  =>  'text',
            'value' =>  ''
        ],
		'DESCRIPTION' => [
            'type'  =>  'text',
            'value' =>  ''
        ],
		'TAGS' => [
            'type'  =>  'tags',
            'value' =>  ''
        ],
		'PUBTIME' => [
            'type'  =>  'datetime',
            'value' =>  DATETIME
        ],
		'RANK' => [
            'type'  =>  'options',
            'value' =>  6
        ],
        'LEVEL' => [
            'type'  =>  'options',
            'value' =>  0
        ],
		'IS_TOP' => [
            'type'  =>  'radio',
            'value' =>  0
        ],
		'KEY_MTIME' => [
            'type'  =>  '',
            'value' =>  DATETIME
        ],
		'KEY_STATE' => [
            'type'  =>  'datetime',
            'value' =>  1
        ],
		'KEY_COUNT' => [
            'type'  =>  'int',
            'value' =>  0
        ],
		'KEY_IS_RECYCLED' => [
            'type'  =>  'datetime',
            'value' =>  0
        ]
	],
    /*  预设主要有如下10种基本类型
     *  **  base 通　　用类型         精简型/复合型/另类型
     *  **  msgs 消　　息类型         公告/通知等类型
     *  **  arti 文　　章类型         文学性/学术性文章类型
     *  **  news 新闻资讯类型         时效性文章类型
     *  **  down 资　　源类型         用于被下载的资源类型
     *  **  play 媒体资源类型         用于直接展播的资源类型，如图片、音乐、视频
     *  **  ablm 图集影集类型         成组的媒体资源类型，如图集、歌单、剧集
     *  **  wiki 词　　条类型         描述物体及其属性的对象类型
     *  **  item 项目产品类型         描述一般具象物体及其属性的对象类型
     *  **  resm 个人履历类型         描述人物及其特征的对象类型
     */
    $basetypes = [
        'base'  =>   [
            'KEYWORDS' => [
                'type'  =>  'text',
                'value' =>  ''
            ],
			'KEY_LIMIT' => [
                'type'  =>  'radio',
                'value' =>  0
            ],
            'CHARGE_TYPE' => [
                'type'  =>  'radio',
                'value' =>  1
            ],
			'CHARGE_VALUE' => [
                'type'  =>  'number',
                'value' =>  0
            ],
			'RECHARGE_HOURS' => [
                'type'  =>  'number',
                'value' =>  0
            ],
            'RECHARGE_TIMES' => [
                'type'  =>  'number',
                'value' =>  0
            ],
            'RELATES' => [
                'type'  =>  'longtext',
                'value' =>  ''
            ],
			'PARTICIPANT' => [
                'type'  =>  'longtext',
                'value' =>  ''
            ],
            'KEY_CTIME' => [
                'type'  =>  '',
                'value' =>  DATETIME
            ]
        ],
        'msgs'	=>	[
			'CONTENT'		=>	[
                'type'  =>  'longtext',
                'value' =>  ''
            ]
		],
		'arti'	=>	[
			'AUTHOR'		=>	[
                'type'  =>  'text',
                'value' =>  ''
            ],
			'SOURCE'		=>	[
                'type'  =>  'text',
                'value' =>  ''
            ],
			'GENRE'			=>	[
                'type'  =>  'text',
                'value' =>  ''
            ],
			'CONTENT'		=>	[
                'type'  =>  'longtext',
                'value' =>  ''
            ]
		],
        'news'	=>	[
			'PRIMER'		=>	[
                'type'  =>  'text',
                'value' =>  ''
            ],
			'SUBTITLE'		=>	[
                'type'  =>  'text',
                'value' =>  ''
            ],
			'AUTHOR'		=>	[
                'type'  =>  'text',
                'value' =>  ''
            ],
			'SOURCE'		=>	[
                'type'  =>  'text',
                'value' =>  ''
            ],
			'CONTENT'		=>	[
                'type'  =>  'longtext',
                'value' =>  ''
            ]
		],
		'down'	=>	[
			'SRC'			=>	[
                'type'  =>  'url',
                'value' =>  ''
            ],
			'DESCRIPTION'	=>	[
                'type'  =>  'text',
                'value' =>  ''
            ]
		],
		'play'	=>	[
			'THUMB'			=>	[
                'type'  =>  'file',
                'value' =>  ''
            ],
			'SRC'			=>	[
                'type'  =>  'url',
                'value' =>  ''
            ],
            'ARTIST'		=>	[
                'type'  =>  'text',
                'value' =>  ''
            ],
			'DESCRIPTION'	=>	[
                'type'  =>  'text',
                'value' =>  ''
            ]
		],
		'ablm'	=>	[
			'THUMB'			=>	[
                'type'  =>  'file',
                'value' =>  ''
            ],
            'ARTIST'		=>	[
                'type'  =>  'text',
                'value' =>  ''
            ],
			'IMAGES'		=>	[
                'type'  =>  'files',
                'value' =>  ''
            ],
			'DESCRIPTION'	=>	[
                'type'  =>  'text',
                'value' =>  ''
            ]
		],
		'wiki'	=>	[
			'IMAGE'			=>	[
                'type'  =>  'file',
                'value' =>  ''
            ],
			'MEANING'		=>	[
                'type'  =>  'longtext',
                'value' =>  ''
            ]
		],
		'item'	=>	[
			'IMAGE'			=>	[
                'type'  =>  'file',
                'value' =>  ''
            ],
			'DETAILS'		=>	[
                'type'  =>  'longtext',
                'value' =>  ''
            ]
		],
		'resm'	=>	[
            'NAME'			=>	[
                'type'  =>  'text',
                'value' =>  ''
            ],
            'ALIAS'			=>	[
                'type'  =>  'text',
                'value' =>  ''
            ],
            'SEX'			=>	[
                'type'  =>  'options',
                'value' =>  2,
            ],
            'PHOTO'			=>	[
                'type'  =>  'file',
                'value' =>  ''
            ],
			'RESUME'		=>	[
                'type'  =>  'longtext',
                'value' =>  ''
            ]
		]
    ];

    public static function all($basetype = NULL){
        $objs = [];
        if(isset(self::$basetypes[$basetype])){
            $presets = Preset::byBasicType($basetype);
            foreach($presets as $ps){
                $obj = new self($ps->basic_type);
                $obj->append(Field::byType($ps->id));
                $objs[] = $obj;
            }
        }else{
            $presets = Preset::all();
            foreach($presets as $ps){
                $obj = new self($ps->basic_type);
                $obj->append(Field::byType($ps->id));
                $objs[] = $obj;
            }
        }
        return $objs;
    }

    public static function byBase($basetype = 'base'){
        return new self($basetype);
    }

    public static function byType($preset){
        if(is_numeric($preset)){
			if($ps = Preset::id($preset)){
                $obj = new self($ps->basic_type);
                return $obj->append(Field::byType($ps->id));
            }
		}elseif(preg_match('/^\w+$/', $preset)){
            if($ps = Preset::alias($preset)){
                $obj = new self($ps->basic_type);
                return $obj->append(Field::byType($ps->id));
            }
		}
        return false;
    }
    
    private function __construct($basetype = 'base'){
        if($basetype != 'base'&&isset(self::$basetypes[$basetype])){
            $this->data = array_merge(self::$defaults, self::$basetypes['base'], self::$basetypes[$basetype]);
        }else{
            $this->data = array_merge(self::$defaults, self::$basetypes['base']);
        }
    }

    protected function append($data){
        foreach($data as $field){
            $this->data[$field->name] = [
                'type'  =>  'custom',
                'value' =>  $field->toArray()
            ];
        }
        return $this;
    }

    public function values($type = 'all'){
        $array = [];
        if($type == 'all'){
            foreach($this->data as $fieldname=>$field){
                if($field['type']=='custom'){
                    $array[$fieldname] = $field['value']['default_value'];
                }else{
                    $array[$fieldname] = $field['value'];
                }
            }
        }elseif($type == 'custom'){
            foreach($this->data as $fieldname=>$field){
                if($field['type']=='custom'){
                    $array[$fieldname] = $field['value']['default_value'];
                }
            }
        }elseif($type == 'extends'){
            foreach($this->data as $fieldname=>$field){
                if(!array_key_exists($fieldname, self::$defaults)){
                    $array[$fieldname] = $field['value'];
                }
            }
        }elseif($type == 'defaults'){
            foreach(self::$defaults as $fieldname=>$field){
                $array[$fieldname] = $field['value'];
            }
        }
        return $array;
    }

    public function postdata(){}
}
