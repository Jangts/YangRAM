static events = {
	'ico.stk-icon' : {
				'click' (){
					__thisapp__.open('default');
				}
			},
			'tools.stk-ctrl item' : {
				'click' (){
					if(!YangRAM.API.DOM.hasClass(this, 'unavailable')){
						var type =YangRAM.attr(this, 'x-usefor');
						typeof self.controls[type] == 'function' && self.controls[type]();
					};
				}
			}
    };