<?php
/*
 * NIML Compiler
 */
trait NIML_traits_optimizer_branches {
	protected function branch_transer($segment, $theme, $indent){
		if(isset($segment['name'])){
			switch($segment['name']){
				case 'if':
				$this->if_transer($segment, $theme, $indent);
				break;

                case 'switch':
				$this->switch_transer($segment, $theme, $indent);
				break;
			}
		}else{
			exit('NIML_COMPILATION_ERROR: NUKNOW BRANCH EXPRESSION');
		}
	}

    protected function if_transer($segment, $theme, $indent){
        if(isset($segment['branches'])&&is_array($segment['branches'])){
            $count = count($segment['branches']);
            foreach($segment['branches'] as $n=>$branch){
                if($branch['type']=='If'){
                    if($n==0){
                        $this->ast['body'][] = array(
                            'type'	=>	'InLineExpression',
                            'indent'=>	$indent,
                            'value'	=>	'if('
                        );
                        $this->condition_transer($branch['condition']);
                        $this->ast['body'][] = array(
                            'type'	=>	'CloseBracketWithOpenBrace',
                            'indent'=>	0,
                            'value'	=>	'){'
                        );
                        $this->walk($branch['body'], $theme, $indent + 1);
                        $this->ast['body'][] = array(
			                'type'	=>	'CloseBrace',
                            'indent'=>	$indent,
			                'value'	=>	'}'
            		    );
                    }else{
                        exit('NIML_COMPILATION_ERROR: EXPRSSION IF MUST AT FIRST');
                    }
                }elseif($branch['type']=='ElseIf'){
                    if($n){
                        $this->ast['body'][] = array(
                            'type'	=>	'Expression',
                            'indent'=>	$indent,
                            'value'	=>	'elseif('
                        );
                        $this->condition_transer($branch['condition']);
                        $this->ast['body'][] = array(
                            'type'	=>	'CloseBracketWithOpenBrace',
                            'indent'=>	0,
                            'value'	=>	'){'
                        );
                        $this->walk($branch['body'], $theme, $indent + 1);
                        $this->ast['body'][] = array(
			                'type'	=>	'CloseBrace',
                            'indent'=>	$indent,
			                'value'	=>	'}'
            		    );
                    }else{
                        exit('NIML_COMPILATION_ERROR: EXPRSSION ELSEIF CANNOT AT FIRST');
                    }
                }elseif($branch['type']=='Else'){
                    if($n==$count-1){
                        $this->ast['body'][] = array(
                            'type'	=>	'Expression',
                            'indent'=>	$indent,
                            'value'	=>	'else{'
                        );
                        $this->walk($branch['body'], $theme, $indent + 1);
                        $this->ast['body'][] = array(
			                'type'	=>	'CloseBrace',
                            'indent'=>	$indent,
			                'value'	=>	'}'
            		    );
                    }else{
                        exit('NIML_COMPILATION_ERROR: EXPRSSION ELSE MUST AT LAST');
                    }
                }
            }
        }
    }

    protected function condition_transer($condition){
        if(count($condition)==1){
            $this->condition_walk($condition[0]);
        }else{
            foreach($condition as $segment){
                if($segment['type']=='ConditionSegment'){
                    $this->condition_walk($segment);
                }elseif($segment['type']=='JointMark'){
                    $this->ast['body'][] = array(
                        'type'	=>  'InLineExpression',
                        'indent'=>	0,
                        'value'	=>  $segment['value']
                    );
                }
            }
        }
    }

    protected $condition_symbols = array(
        '='     =>  '==',
        'eq'    =>  '==',
        'is'    =>  '===',
        'ne'    =>  '!=',
        'not'   =>  '!==',
        'ge'    =>  '>=',
        'gt'    =>  '>',
        'le'    =>  '<=',
        'lt'    =>  '<'
    );

    protected function condition_walk($segment){
        if(!empty($segment['outleft'])){
            $this->ast['body'][] = array(
                'type'	=>  'InLineExpression',
                'indent'=>	0,
                'value'	=>  $segment['outleft']
            );
        }
        
        $this->ast['body'][] = array(
            'type'	=>  'InLineExpression',
            'indent'=>	0,
            'value'	=>  $this->condition_check($segment['inner']['left'])
        );

        $symbol = $segment['inner']['symbol'];
        $this->ast['body'][] = array(
            'type'	=>  'InLineExpression',
            'indent'=>	0,
            'value'	=>  isset($this->condition_symbols[$symbol]) ? $this->condition_symbols[$symbol] : '=='
        );

        $this->ast['body'][] = array(
            'type'	=>  'InLineExpression',
            'indent'=>	0,
            'value'	=>  $this->expression_agreement_generator($segment['inner']['right'])
        );

        if(!empty($segment['outright'])){
            $this->ast['body'][] = array(
                'type'	=>  'InLineExpression',
                'indent'=>	0,
                'value'	=>  $segment['outright']
            );
        }
    }

    protected function condition_check($item){
        switch ($item['type']) {
            case 'Expression':
            return $item['value'];

            case 'Variable':
            $var = self::variable_transformer($item['value']);
			return "isset($var) && $var";

            case 'Const':
			$const  = $this->expression_agreement_generator($item);
            return "defined($const) && $const";

            case 'MathExpression':
			case 'StaticMember':
			$value  = $this->expression_agreement_generator($item);
            return $value;

			case 'ArrayElement':
            case 'StaticArrayElement':
			$elem  = $this->expression_agreement_generator($item);
            return "isset($elem) && $elem";
            
            default:
			return $this->expression_agreement_generator($item);
		}
    }

	protected function switch_transer($segment, $theme, $indent){
		if(isset($segment['identity'])&&isset($segment['branches'])&&is_array($segment['branches'])){
            $this->ast['body'][] = array(
				'type'	=>	'Expression',
                'indent'=>	$indent,
				'value'	=>	'switch(' . self::variable_transformer($segment['identity']) . '){'
			);
            foreach($segment['branches'] as $branch){
                $this->switch_branch_transer($branch, $theme, $indent + 1);
            }
		    $this->ast['body'][] = array(
			    'type'	=>	'CloseBrace',
                'indent'=>	$indent,
			    'value'	=>	'}'
		    );
        }
	}

    protected function switch_branch_transer($branch, $theme, $indent){
        if(isset($branch['type'])){
            if($branch['type']=='Case'){
                $this->ast['body'][] = array(
				    'type'	=>	'Expression',
                    'indent'=>	$indent,
				    'value'	=>	'case \'' . $branch['condition'] . '\':'
			    );
                if(!empty($branch['body'])) $this->walk($branch['body'], $theme, $indent);
            }elseif($branch['type']=='Default'&&!empty($branch['body'])){
                $this->ast['body'][] = array(
				    'type'	=>	'Expression',
                    'indent'=>	$indent,
				    'value'	=>	'default:'
			    );
                $this->walk($branch['body'], $theme, $indent);
            }
        }
		
	}
}
