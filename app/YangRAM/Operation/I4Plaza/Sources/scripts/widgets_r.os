static widgetRenderers = {
        charts(alias, className, list, height) {
            var widget = self.widgets[alias].widget;
            require([
                'echarts',
                'echarts/../theme/dark',
                'echarts/chart/pie',
                'echarts/chart/bar',
                'echarts/chart/line',
                'echarts/component/title',
                'echarts/component/legend',
                'echarts/component/grid',
                'echarts/component/tooltip'
            ], function(echarts, theme) {
                widget.innerHTML = '';
                YangRAM.API.util.arr.each(list, function(i, data) {
                    var el = YangRAM.create('v', widget, {
                        className: className,
                        height: height
                    });
                    var myChart = echarts.init(el, 'dark');
                    //console.log(myChart);
                    myChart.setOption(data);
                });
                YangRAM.create('click', widget, {
                    className: 'widget-link',
                    href: 'launch://' + self.widgets[alias].appid+'::launch',
                    html: '查看详情'
                });
            });
        },
        stripe(alias, className, list, height) {
            var widget = self.widgets[alias].widget,
                title = self.widgets[alias].title,
                nums = [' odd', ' even'];
            widget.innerHTML = '<v class="widget-title">' + title + '<v>';
            YangRAM.API.util.arr.each(list, function(i, data) {
                var el = YangRAM.create('v', widget, {
                    className: className + nums[i % 2],
                    height: height,
                    html: '<el class="for-type">[' + data.MARK + ']</el>' + data.TITLE + '<el class="for-time">[' + data.TIME + ']</el>'
                });
            });
            YangRAM.create('click', widget, {
                className: 'widget-link',
                href: 'launch://' + self.widgets[alias].appid+'::launch',
                html: 'See Details'
            });
        }
};