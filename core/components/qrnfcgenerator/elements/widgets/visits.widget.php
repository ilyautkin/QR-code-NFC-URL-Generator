<?php
/**
 * QRNFCGenerator.
 */
class modDashboardWidgetQRNFCGeneratorVisits extends modDashboardFileWidget
{
    /**
     * @acces public.
     * @return String.
     */
    public function render()
    {
        $this->qrnfcgenerator = $this->modx->getService(
            'qrnfcgenerator',
            'QRNFCGenerator',
            $this->modx->getOption(
                'qrnfcgenerator.core_path',
                null,
                $this->modx->getOption('core_path') . 'components/qrnfcgenerator/'
            ) . 'model/qrnfcgenerator/'
        );

        $this->modx->regClientCSS($this->qrnfcgenerator->config['css_url'] . 'mgr/qrnfcgenerator.css');

        $this->modx->lexicon->load('qrnfcgenerator:default');
        $this->modx->controller->setPlaceholder('_lang', $this->modx->lexicon->fetch());

        return $this->modx->smarty->fetch(
            $this->qrnfcgenerator->config['templates_path'] . 'widget.visits.tpl',
            [
                'dates'       => [
                    'cur_week'  => [
                        'start' => date('d/m', strtotime('-1 week')),
                        'end'   => date('d/m')
                    ],
                    'past_week' => [
                        'start' => date('d/m', strtotime('-2 weeks')),
                        'end'   => date('d/m', strtotime('-1 week'))
                    ]
                ],
                'data' => $this->getData()
            ]
        );
    }

    /**
     * Retrieve data.
     *
     * @return array
     */
    protected function getData()
    {
        $records = [];

        $query = $this->modx->newQuery('QRNFCVisit');
        $query->select([
            'modResource.*'
        ]);
        $query->leftJoin('modResource', 'modResource', 'modResource.id = QRNFCVisit.resource');

        /* Only retrieve from last two weeks. */
        $query->where([
            'createdon:>=' => date('Y-m-d', strtotime('-2 weeks'))
        ]);

        $query->groupby('resource');

        $query->prepare();
        $resources = $this->modx->getIterator('QRNFCVisit', $query);
        if ($resources) {
            foreach ($resources as $resource) {
                $array                      = $resource->toArray();
                $array['hits']['cur_week']  = $this->getHitsForResource($resource->get('id'), date('Y-m-d', strtotime('-1 week')));
                $array['hits']['past_week'] = $this->getHitsForResource($resource->get('id'), date('Y-m-d', strtotime('-2 weeks')),  date('Y-m-d', strtotime('-1 week')));

                $array['hits']['change'] = 'equal';
                if ($array['hits']['cur_week'] > $array['hits']['past_week']) {
                    $array['hits']['change'] = 'increase';
                } elseif ($array['hits']['cur_week'] < $array['hits']['past_week']) {
                    $array['hits']['change'] = 'decrease';
                }

                $records[] = $array;
            }
        }

        return [
            'records' => $records
        ];
    }

    /**
     * Return amount of hits per resource.
     *
     * @param $resourceId
     * @param $from
     * @param null $till
     * @return int
     */
    protected function getHitsForResource($resourceId, $from, $till = null)
    {
        $query = $this->modx->newQuery('QRNFCVisit');
        $query->where([
            'resource'     => $resourceId,
            'createdon:>=' => $from
        ]);

        if ($till) {
            $query->where([
                'createdon:<=' => $till
            ]);
        }

        return $this->modx->getCount('QRNFCVisit', $query);
    }
}

return 'modDashboardWidgetQRNFCGeneratorVisits';
