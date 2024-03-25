<?php

namespace MageBig\OrderComment\Plugin\Model\Variable;

class VariablePlugin
{
    public function aroundGetVariablesOptionArray($subject, $proceed, $withGroup = false)
    {
        $result = $proceed($withGroup);

        $variables = [];
        $variables[] = [
            'value' => '{{if magebig_order_comment}}
<table class="message-info">
    <tr>
        <td>
            {{var magebig_order_comment|raw|escape|nl2br}}
        </td>
    </tr>
</table>
{{/if}}',
            'label' => __('Order Comment'),
        ];

        $variables = [['label' => __('New Order Email Template'), 'value' => $variables]];

        return array_merge($variables, $result);
    }
}
