<div class="qrnfcgenerator-widget-visits">
    <p>{$description}</p>
    <br/>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>{$_lang['id']}</th>
                    <th>{$_lang['pagetitle']}</th>
                    <th>{$_lang['visits']}<span class="visits-date">({$dates.cur_week.start} - {$dates.cur_week.end})</span></th>
                    <th>Visits <span class="visits-date">({$dates.past_week.start} - {$dates.past_week.end})</span></th>
                </tr>
            </thead>
            <tbody>
                {if count($data.records) === 0}
                    <td colspan="4">
                        <p>There is no data available yet.</p>
                    </td>
                {else}
                    {foreach $data.records as $record}
                        <tr>
                            <td>
                                {$record.id}
                            </td>
                            <td>
                                {$record.pagetitle}
                            </td>
                            <td>
                                <span class="visits__change visits__change--{$record.hits.change}">
                                    {$record.hits.cur_week}
                                </span>
                            </td>
                            <td>
                                {$record.hits.past_week}
                            </td>
                        </tr>
                    {/foreach}
                {/if}
            </tbody>
        </table>
    </div>
</div>