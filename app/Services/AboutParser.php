<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

class AboutParser
{
    use \App\Helpers\StringTrait;

    protected $aProperties = array(
        'золото' => 'https://www.fantasyland.ru/images/items/coins.gif',
        'золотые монеты' => 'https://www.fantasyland.ru/images/items/coins.gif',
        'ум' => 'https://www.fantasyland.ru/images/miscellaneous/quest.gif',
        'Уровень' => 'http://citadel-liga.info/img/games/lvl.gif',
        'Длительность' => 'https://www.fantasyland.ru/images/miscellaneous/time.gif',
        'Ум' => 'https://www.fantasyland.ru/images/miscellaneous/intelligence.gif',
        'Сила' => 'https://www.fantasyland.ru/images/miscellaneous/strength.gif',
        'Тип' => 'https://www.fantasyland.ru/images/i/b1.gif',
        'Атака' => 'https://www.fantasyland.ru/images/miscellaneous/attack_all.gif',
        'Атака Драконов' => 'https://www.fantasyland.ru/images/miscellaneous/attack_d.gif',
        'Атака Рыцарей' => 'https://www.fantasyland.ru/images/miscellaneous/attack_k.gif',
        'Атака Дам' => 'https://www.fantasyland.ru/images/miscellaneous/attack_l.gif',
        'Атака Хаоса' => 'https://www.fantasyland.ru/images/miscellaneous/attack_c.gif',
        'Атака Света' => 'https://www.fantasyland.ru/images/miscellaneous/attack_h.gif',
        'Атака Колдовства' => 'https://www.fantasyland.ru/images/miscellaneous/attack_s.gif',
        'Атака Астрала' => 'https://www.fantasyland.ru/images/miscellaneous/attack_a.gif',
        'Защита' => 'https://www.fantasyland.ru/images/miscellaneous/defence_all.gif',
        'Защита от Драконов' => 'https://www.fantasyland.ru/images/miscellaneous/defence_d.gif',
        'Защита от Рыцарей' => 'https://www.fantasyland.ru/images/miscellaneous/defence_k.gif',
        'Защита от Дам' => 'https://www.fantasyland.ru/images/miscellaneous/defence_l.gif',
        'Защита от Хаоса' => 'https://www.fantasyland.ru/images/miscellaneous/defence_c.gif',
        'Защита от Света' => 'https://www.fantasyland.ru/images/miscellaneous/defence_h.gif',
        'Защита от Колдовства' => 'https://www.fantasyland.ru/images/miscellaneous/defence_s.gif',
        'Защита от Астрала' => 'https://www.fantasyland.ru/images/miscellaneous/defence_a.gif',
        'Защита от Яда' => 'https://www.fantasyland.ru/images/miscellaneous/pp.gif',
        'Cила Эффектов' => 'https://www.fantasyland.ru/images/miscellaneous/m_prot.gif',
        'Восстановление Жизни' => 'https://www.fantasyland.ru/images/miscellaneous/regen_hp.gif',
        'Концентрация' => 'https://www.fantasyland.ru/images/miscellaneous/conc.gif',
        'Удача' => 'https://www.fantasyland.ru/images/miscellaneous/luck.gif',
        'Размер' => 'https://www.fantasyland.ru/images/miscellaneous/size_pl.png',
        'Число Ударов' => 'https://www.fantasyland.ru/images/miscellaneous/size_sword.png',
        'Обучаемость' => 'https://www.fantasyland.ru/images/miscellaneous/learn.gif',
        'Скорость' => 'https://www.fantasyland.ru/images/miscellaneous/speed.gif',
        'Макс. Жизнь' => 'https://www.fantasyland.ru/images/miscellaneous/hp.gif',
        'Сила Эффектов' => 'https://www.fantasyland.ru/images/miscellaneous/m_prot.gif',
        'Власть над Драконами' => 'https://www.fantasyland.ru/images/miscellaneous/mastery_d.gif',
        'Власть над Рыцарями' => 'https://www.fantasyland.ru/images/miscellaneous/mastery_k.gif',
        'Власть над Дамами' => 'https://www.fantasyland.ru/images/miscellaneous/mastery_l.gif',
        'Магия Хаоса' => 'https://www.fantasyland.ru/images/miscellaneous/magic_c.gif',
        'Магия Света' => 'https://www.fantasyland.ru/images/miscellaneous/magic_h.gif',
        'Магия Астрала' => 'https://www.fantasyland.ru/images/miscellaneous/magic_a.gif',
        'Колдовство' => 'https://www.fantasyland.ru/images/miscellaneous/magic_s.gif',
        'Атака Всех' => 'https://www.fantasyland.ru/images/miscellaneous/attack_all.gif',
        'Атака трех магий' => 'https://www.fantasyland.ru/images/miscellaneous/attack_mall.gif',
        'Атака Трех Магий' => 'https://www.fantasyland.ru/images/miscellaneous/attack_mall.gif',
        'Атака Всех Магий' => 'https://www.fantasyland.ru/images/miscellaneous/attack_mall.gif',
        'Защита от Всех Магий' => 'https://www.fantasyland.ru/images/miscellaneous/defence_mall.gif',
        'Защита от трех магий' => 'https://www.fantasyland.ru/images/miscellaneous/defence_mall.gif',
        'Защита от Трех Магий' => 'https://www.fantasyland.ru/images/miscellaneous/defence_mall.gif',
        'Защита от Нейтральных Заклятий' => 'https://www.fantasyland.ru/images/effects/pn_15.png',
        'Защита от Нейтральных За' => 'https://www.fantasyland.ru/images/effects/pn_15.png',
        'Защита от Всех' => 'https://www.fantasyland.ru/images/miscellaneous/defence_all.gif',
        'Защита от Нейтральных Заклятий' => 'https://www.fantasyland.ru/images/miscellaneous/pn.gif',
        'Защита от Нейтральных За' => 'https://www.fantasyland.ru/images/miscellaneous/pn.gif',

        'Рецепт Купцов' => 'https://www.fantasyland.ru/images/clans/merch_small.gif',
        'Рецепт Кузнецов' => 'https://www.fantasyland.ru/images/clans/smith_capital_small.gif',
        'Рецепт Ювелиров' => 'https://www.fantasyland.ru/images/clans/jewelry_small.gif',
        'Рецепт Портных' => 'https://www.fantasyland.ru/images/clans/tailors_small.gif',
        'Рецепт Алхимиков' => 'https://www.fantasyland.ru/images/clans/alchemists_small.gif',
        'Рецепт Мудрецов' => 'https://www.fantasyland.ru/images/clans/thinkers_small.gif',
        'Рецепт Фальшивомонетчиков' => 'https://www.citadel-liga.info/images/empty.gif',
        'Рецепт Искусных Мастеров' => 'https://www.fantasyland.ru/images/clans/fim_small.png',
        'Рецепт Механистов' => 'https://www.fantasyland.ru/images/clans/mechanists_small.png',
    );

    protected function findProperty(string $sPropName) {
        $aSearch = [];
        foreach (array_keys($this->aProperties) as $sValue) {
            $aSearch[$sValue] = similar_text(str_replace('&nbsp;', ' ', $sPropName),  $sValue);
        }
        arsort($aSearch);
        return key($aSearch);
    }

    protected function fixHtml(string $content, bool $closeTagsOnly = false) {
        if (!$closeTagsOnly) {
            $content = preg_replace("/<(meta|body|html|style|script|base)(\\s+.*?>|>)/", "", $content);
            $content = preg_replace("/<(meta|body|html|style|script|base)/", "", $content);
            $content = preg_replace("/<\\/?(meta|body|html|style|script|base)(\\s+.*?>|>)/", "", $content);
            $content = str_ireplace(array('\\', '&', '&amp;amp;', 'style=', 'onabort=', 'onactivate=', 'onafterprint=', 'onafterupdate=', 'onbeforeactivate=', 'onbeforecopy=', 'onbeforecut=', 'onbeforedeactivate=', 'onbeforeeditfocus=', 'onbeforepaste=', 'onbeforeprint=', 'onbeforeunload=', 'onbeforeupdate=', 'onblur=', 'onbounce=', 'oncellchange=', 'onchange=', 'onclick=', 'oncontextmenu=', 'oncontrolselect=', 'oncopy=', 'oncut=', 'ondataavaible=', 'ondatasetchanged=', 'ondatasetcomplete=', 'ondblclick=', 'ondeactivate=', 'ondrag=', 'ondragdrop=', 'ondragend=', 'ondragenter=', 'ondragleave=', 'ondragover=', 'ondragstart=', 'ondrop=', 'onerror=', 'onerrorupdate=', 'onfilterupdate=', 'onfinish=', 'onfocus=', 'onfocusin=', 'onfocusout=', 'onhelp=', 'onkeydown=', 'onkeypress=', 'onkeyup=', 'onlayoutcomplete=', 'onload=', 'onlosecapture=', 'onmousedown=', 'onmouseenter=', 'onmouseleave=', 'onmousemove=', 'onmoveout=', 'onmouseover=', 'onmouseup=', 'onmousewheel=', 'onmove=', 'onmoveend=', 'onmovestart=', 'onpaste=', 'onpropertychange=', 'onreadystatechange=', 'onreset=', 'onresize=', 'onresizeend=', 'onresizestart=', 'onrowexit=', 'onrowsdelete=', 'onrowsinserted=', 'onscroll=', 'onselect=', 'onselectionchange=', 'onselectstart=', 'onstart=', 'onstop=', 'onsubmit=', 'onunload=', '<!--', '-->', 'javascript'), array('/','&amp;','&amp;'), $content);
        }
        $aContent = preg_split('/(<[^>]*[^\/]>)/i', $content, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $aStack = [];
        $sResult = '';
        foreach ($aContent as $i => $sValue) {
            $sValue = str_ireplace(array(' on', '"on',"'on"), array('', '"',"'"), $sValue);
            if (preg_match('{[a-zA-Z0-9:]{1,}}si', $sValue, $aTag)) {
                $iSize = sizeof($aStack);
                while ($iSize && in_array($aTag[0], $aStack)) {
                    $sResult .= "</{$aStack[$iSize-1]}>";
                    unset($aStack[$iSize-1]);
                    $aStack = array_values($aStack);
                    $iSize = sizeof($aStack);
                }
                if (strpos(' ' . $sValue, "<{$aTag[0]}") && !strpos($sValue, '/>') && !strpos(' ' . $sValue, '</')) {
                    if (in_array($aTag[0], array('br','link','img'))) {
                        $sValue = substr(trim($sValue), 0, -1).' />';
                    } else {
                        $aStack[] = $aTag[0];
                    }
                }
                if (!strpos(' ' . $sValue, "</{$aTag[0]}")) {
                    $sResult .= $sValue;
                }
            } else {
                $sResult .= $sValue;
            }
        }

        $iSize = sizeof($aStack);
        while ($iSize) {
            $sResult .= "</{$aStack[$iSize-1]}>";
            unset($aStack[$iSize-1]);
            $aStack = array_values($aStack);
            $iSize = sizeof($aStack);
        }

        if ($closeTagsOnly) {
            return $sResult;
        } else {
            return preg_replace(
                array(
                    '@<head[^>]*?>.*?</head>@siu',
                    '@<style[^>]*?>.*?</style>@siu',
                    '@<script[^>]*?.*?</script>@siu',
                    '@<object[^>]*?.*?</object>@siu',
                    '@<embed[^>]*?.*?</embed>@siu',
                    '@<applet[^>]*?.*?</applet>@siu',
                    '@<noframes[^>]*?.*?</noframes>@siu',
                    '@<noscript[^>]*?.*?</noscript>@siu',
                    '@<noembed[^>]*?.*?</noembed>@siu',
                    '@</?((frameset)|(frame)|(iframe))@iu',
                ),
                '',
                $sResult
            );
        }
    }

    public function item(string $html) {
        $sContent = $this->fixHtml(
            '<html><HEAD><meta http-equiv="content-type" content="text/html; charset=utf-8" />'.
            str_replace(
                array('<br>', '<BR>', '&nbsp;','<HEAD>'),
                array('<br />','<br />', ' '),
                $html
            ),
            true
        );
        $o = new \DOMDocument();
        @$o->loadHTML($sContent);
        if (!$o || !($sName = $o->getElementsByTagName('title')->item(0)->textContent)) {
            return [
                'name' => '?',
                'lvl' => 0,
                'cost' => 0,
                'cost_type' => '',
                'image' => '',
                'description' => '',
                'properties' => [],
                'made' => [],
            ];
        }

        $oProp = $o->getElementsByTagName('table')->item(1)->getElementsByTagName('td');
        $aProp = [];
        for ($i = 0; $i < $oProp->length; $i = $i+2) {
            $sPropName = trim($oProp->item($i)->textContent);
            if ($sPropName === 'Цена:') {
                $aProp[$sPropName] = [
                    $oProp->item($i+1)->textContent,
                    $oProp->item($i+1)->getElementsByTagName('img')->item(0)->getAttribute('title') === 'Золота' ? 'золото' : 'ум'
                ];

            } elseif ($sPropName === 'Длительность:') {
                $aProp['properties'][] = array(
                    'type' => 'property',
                    'property' => 'Длительность',
                    'value'    => trim($oProp->item($i+1)->textContent)
                );

            } elseif (in_array($sPropName, array('Эффекты:','Эффект:'))) {
                $oImg = $oProp->item($i+1)->getElementsByTagName('img');
                for ($k = 0; $k < $oImg->length; $k++) {
                    $aText = explode("\n", $oImg->item($k)->getAttribute('title'));
                    $sEffect = $aText[sizeof($aText)-1];
                    $sCount = '';
                    if (preg_match("/на \d{1,} ход/", $sEffect, $aLen)) {
                        $sEffect = substr($sEffect, strpos($sEffect, $aLen[0]) + strlen($aLen[0]));
                        if (strpos($sEffect, 'ов ') === 0) {
                            $sEffect = $this->substr($sEffect, 3);
                        }
                        $sCount = ' (x'.filter_var($aLen[0], FILTER_SANITIZE_NUMBER_INT).')';
                    }

                    $aProp['properties'][] = array(
                        'type' => 'property',
                        'property' => $this->findProperty( $sEffect ),
                        'value'    => filter_var($sEffect, FILTER_SANITIZE_NUMBER_INT) . $sCount
                    );
                    $aType = explode(':', trim($aText[sizeof($aText)-2]));
                    if ($aType[0] === 'Тип') {
                        $aProp['properties'][] = array(
                            'type' => 'property',
                            'property' => $aType[0],
                            'value'    => $aType[1]
                        );
                    }
                }

            } elseif (in_array($sPropName, array('Свойства:','Требования:'))) {
                $oImg = $oProp->item($i+1)->getElementsByTagName('img');
                $oB = $oProp->item($i+1)->getElementsByTagName('b');
                for ($k = 0; $k < $oImg->length; $k++) {
                    $aProp['properties'][] = array(
                        'type' => ($sPropName === 'Требования:') ? 'required' : 'property',
                        'property' => $oImg->item($k)->getAttribute('title'),
                        'value'    => $oB->item($k)->textContent
                    );
                }

            } else {
                $aProp[$sPropName] = $oProp->item($i+1)->textContent;
            }
        }

        $fAddItem = function($sMadeName, $iGroup, $sType, $sNam, $sVal) {
            return array(
                'who_can' => $sMadeName,
                'group'   => $iGroup,
                'pro_type' => $sType,
                'type' => $sNam,
                'value' => $sVal
            );
        };

        $iMade = 1;
        $aMadeExist = [];
        $aProp['made'] = [];
        while ($oMade = $o->getElementById('moo'.$iMade)) {
            $bRequired = false;
            $sMadeName = $o->getElementsByTagName('script')->item($iMade*2+2)->textContent;
            $sMadeName = explode("GetBlockTitle('", $sMadeName);
            $sMadeName = explode("<", $sMadeName[1]);
            $sMadeName = $sMadeName[0];
            if (isset($aMadeExist[$sMadeName])) {
                $aMadeExist[$sMadeName]++;
            } else {
                $aMadeExist[$sMadeName] = 0;
            }
            $iGroup = $aMadeExist[$sMadeName];

            if (!$oMade->textContent) {
                $sTemp = explode("<div id=moo{$iMade} style='display:none'>", $sContent);
                $sTemp = explode('</div>', $sTemp[1]);
                $oMade = new \DOMDocument();
                $oMade->loadHTML(
                    $this->fixHtml(
                        '<html><meta http-equiv="content-type" content="text/html; charset=utf-8" /></head><body>'.$sTemp[1].'</body></html>',
                        true
                    )
                );
            }
            $oTemp = $oMade->getElementsByTagName('table')->item(0)->getElementsByTagName('td');
            $iCount = $oTemp->length/2;
            for ($j = 0; $j < $iCount; $j++) {
                $aProp['made'][] = $fAddItem(
                    $sMadeName, $iGroup, 'items',
                    $oTemp->item($j)->getElementsByTagName('a')->item(0)->getAttribute('title'),
                    $oTemp->item($j+$iCount)->textContent
                );
            }

            $aTemp = [];
            $oTemp = $oMade->childNodes;
            $bSkip = true;
            for ($j = 0; $j < $oTemp->length; $j++) {
                $aTemp = explode(':', $oTemp->item($j)->textContent);
                if ($aTemp[0] === 'Монет') {
                    $aProp['made'][] = $fAddItem(
                        $sMadeName, $iGroup, 'items',
                        'золотые монеты',
                        $aTemp[1]
                    );
                } elseif ($aTemp[0] === 'Количество') {
                    $aProp['made'][] = $fAddItem(
                        $sMadeName, $iGroup, 'required',
                        $aTemp[0],
                        $oTemp->item($j+1)->textContent
                    );
                } elseif ($aTemp[0] === 'Требования') {
                    $bSkip = false;
                    $bRequired = true;
                    continue;
                }
                if ($bSkip || !$aTemp[0]) {
                    continue;
                }

                $aProp['made'][] = $fAddItem(
                    $sMadeName, $iGroup, 'required',
                    $aTemp[0],
                    $aTemp[1]
                );
            }
            $iMade++;

            if (!$bRequired) {
                $bSkip = true;
                $sPrev = '';
                foreach ($aTemp as $i => $s) {
                    if ($bSkip) {
                        if (strpos(" $s", 'Требования')) {
                            $bSkip = false;
                        }
                        continue;
                    }
                    if (isset($aTemp[$i+1])) {
                        $iNext = (int)$aTemp[$i+1];
                        if (!$iNext) {
                            $iNext = trim($this->substr(trim($aTemp[$i+1]), 0, $this->substr_compare($aTemp[$i+1], $this->strtolower($aTemp[$i+1]), 2)));
                        }
                        $aProp['made'][] = $fAddItem(
                            $sMadeName, $iGroup, 'required',
                            mb_substr(trim($s), mb_strlen($sPrev)),
                            $iNext
                        );
                        $sPrev = "$iNext";
                    }
                }
            }
        }

        if (isset($aProp['Тип:'])) {
            $aProp['properties'][] = array(
                'type' => 'property',
                'property' => 'Тип',
                'value'    => $aProp['Тип:']
            );
        }

        return [
            'name' => $sName,
            'lvl' => $aProp['Уровень:'] ?? 0,
            'cost' => $aProp['Цена:'][0] ?? 0,
            'cost_type' => $aProp['Цена:'][1] ?? 'ум',
            'image' => $o->getElementsByTagName('img')->item(2)->getAttribute('src'),
            'description' => $aProp['Описание:'] ?? '',
            'properties' => $aProp['properties'] ?? [],
            'made' => $aProp['made'] ?? [],
        ];
    }
}
