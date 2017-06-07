static events = {
	'ico.dsr-icon' : {
				'click' (){
					__thisapp__.open('default');
				}
			},
			'tools.dsr-ctrl item' : {
				'click' (){
					if(!YangRAM.API.DOM.hasClass(this, 'unavailable')){
						var type =YangRAM.attr(this, 'x-usefor');
						typeof self.controls[type] == 'function' && self.controls[type]();
					};
				}
			}
    };