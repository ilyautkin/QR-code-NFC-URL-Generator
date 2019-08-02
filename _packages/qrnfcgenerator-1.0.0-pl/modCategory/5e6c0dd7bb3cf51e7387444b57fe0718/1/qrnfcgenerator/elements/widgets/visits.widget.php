<?php
/**
 * Google Analytics
 */
class modDashboardWidgetGoogleAnalyticsVisitors extends modDashboardFileWidget
{
    /**
     * @acces public.
     * @var Object.
     */
    public $googleanalytics;


    /**
     * @acces public.
     * @return String.
     */
    public function render()
    {
        $this->googleanalytics = $this->modx->getService('googleanalytics', 'GoogleAnalytics', $this->modx->getOption('googleanalytics.core_path', null, $this->modx->getOption('core_path').'components/googleanalytics/').'model/googleanalytics/');

        if (false !== ($account = $this->googleanalytics->getAuthorizedProfile())) {
            $this->modx->regClientCSS($this->googleanalytics->config['css_url'].'mgr/googleanalytics.css');

            $this->modx->regClientStartupScript($this->googleanalytics->config['js_url'].'mgr/googleanalytics.js');

            $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
					Ext.onReady(function() {
						GoogleAnalytics.config = '.$this->modx->toJSON(array_merge($this->googleanalytics->config, array(
                    'authorized'			=> $this->googleanalytics->isAuthorized(),
                    'authorized_profile'	=> $this->googleanalytics->getAuthorizedProfile()
                ))).';
					});
				</script>');
            $this->modx->regClientStartupScript($this->googleanalytics->config['js_url'].'mgr/libs/jquery.min.js');
            $this->modx->regClientStartupScript($this->googleanalytics->config['js_url'].'mgr/libs/highcharts.js');
            $this->modx->regClientStartupScript($this->googleanalytics->config['js_url'].'mgr/widgets/home.charts.js');
            $this->modx->regClientStartupScript($this->googleanalytics->config['js_url'].'mgr/sections/visitors.widget.js');
            if (is_array($this->googleanalytics->config['lexicons'])) {
                foreach ($this->googleanalytics->config['lexicons'] as $lexicon) {
                    $this->modx->controller->addLexiconTopic($lexicon);
                }
            } else {
                $this->modx->controller->addLexiconTopic($this->googleanalytics->config['lexicons']);
            }

            $this->widget->name = $this->modx->lexicon('googleanalytics.widget_visitors_title', array(
                'property' => $account['property_id']
            ));

            return $this->modx->smarty->fetch($this->googleanalytics->config['templates_path'].'widget.visitors.tpl');
        }
    }
}

return 'modDashboardWidgetGoogleAnalyticsVisitors';

?>