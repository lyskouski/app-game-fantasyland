document.addEventListener('DOMContentLoaded', () => window.goTo(0));

/*

            <details name="tech-specs">
                <summary>
                    Имя пользователя<br />
                    <label>
                        XP: <progress class="bar" value="20" max="100"></progress>
                    </label>
                </summary>
                <ul>
                    <li>...</li>
                    <li>...</li>
                    <li>...</li>
                </ul>
            </details>

*/

/* https://www.fantasyland.ru/cgi/combat_ref.php?lid=undefined

<script language='javascript'>parent.your_army.update([[125569, 'Росомаха', 'M', 6, 117, 117, 0, 15, 0, 15, 0, 15, 14, 8, 0, 0, 0, 0, [[["blood_lust_06.gif","Жажда&nbsp;Крови&nbsp;+6",18]], ""], 5, 0, 0, 4, 0, 0,
     0, 0, 0, 0]], [1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [[1301, 1, 1], [1401, 2, 1], [1601, 30, 1], [1602, 30, 1], [1664, 6, 1], [1696, 10, 1], [2566, 10, 2], [2577, 6, 2], [2601, 30, 2], [2602, 30, 2], [2606, 1, 2], [2612, 10, 2], [2664, 5, 2], [3601, 30, 3], [3602, 30, 3], [3664, 9, 3]]);
parent.enemy_army.update([[-48959148, 'Танк', '10601', 6, 118, 129, 0, 0, 11, 13, 0, 11, 0, 0, 0, 0, 0, 0, [[], ""], 5, 0, 0, 0, 10, 0,
     0, 0, 0, 0]]);
*/

/* https://www.fantasyland.ru/cgi/armylist_yours.php

var allinfo=[
[[125569, 'Росомаха', 'M', 6, 117, 117, 0, 15, 0, 15, 0, 15, 8, 8, 0, 0, 0, 0, [[], ""], 5, 0, 0, 4, 0, 0,
     0, 0, 0, 0]], [1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [[1301, 1, 1], [1401, 2, 1], [1601, 30, 1], [1602, 30, 1], [1664, 6, 1], [1696, 10, 1], [2566, 10, 2], [2577, 6, 2], [2601, 30, 2], [2602, 30, 2], [2606, 1, 2], [2612, 10, 2], [2664, 5, 2], [3601, 30, 3], [3602, 30, 3], [3664, 10, 3]]];
*/

/* https://www.fantasyland.ru/cgi/armylist_enemy.php

var allinfo=[
[[-48959148, 'Танк', '10601', 6, 129, 129, 0, 0, 11, 13, 0, 11, 0, 0, 0, 0, 0, 0, [[], ""], 5, 0, 0, 0, 10, 0,
     0, 0, 0, 0]]];
*/

   /*

  this.name = info[1];
  this.sex = info[2];
  this.level = info[3];

    infoIcon = "&nbsp;<IMG width='11' height='11' alt='Информация' style='cursor:pointer; cursor:hand;' onClick=\" window.top.oI('"+this.name+"');\" src=\"../images/miscellaneous/info_" + this.sex + ".gif\">";


Player.prototype.Body = function()
{
  var info = this.info;
  var id = this.id;
  var statDiv = document.createElement("div");
  statDiv.id = 'plb'+id;
  cashGE['plb'+id] = statDiv;
  statDiv.style.display=(this.state)?('block'):('none');

  var luckSh = (info[19]==0)?("style='display:none'"):("");
  var regSh = (info[20]==0)?("style='display:none'"):("");
  var learnSh = (info[21]==0)?("style='display:none'"):("");
  var concSh = (info[22]==0)?("style='display:none'"):("");
  var ppSh = (info[23]==0)?("style='display:none'"):("");
  var protSh = (info[24]==0)?("style='display:none'"):("");

  var astralSh = (info[25]==0 && info[26]==0)?("style='display:none'"):("");

  var pnSh = (info[27]==0)?("style='display:none'"):("");

  var addStr = "<td id='luckSh1"+id+"' "+luckSh+"><image width=20 height=20 src='../images/miscellaneous/luck.gif' title='Удача'></td><td nowrap id='luckSh2"+id+"' "+luckSh+"><span id='luck"+id+"'>"+info[19]+"</span></td>";

  addStr += "<td id='regSh1"+id+"' "+regSh+"><image width=20 height=20 src='../images/miscellaneous/regen_hp.gif' title='Восстановление Жизни'></td><td nowrap id='regSh2"+id+"' "+regSh+"><span id='reg"+id+"'>"+info[20]+"</span></td>";

  addStr += "<td id='learnSh1"+id+"' "+learnSh+"><image width=20 height=20 src='../images/miscellaneous/learn.gif' title='Обучаемость'></td><td nowrap id='learnSh2"+id+"' "+learnSh+"><span id='learn"+id+"'>"+info[21]+"</span></td>";

  addStr += "<td id='concSh1"+id+"' "+concSh+"><image width=20 height=20 src='../images/miscellaneous/conc.gif' title='Концентрация'></td><td nowrap id='concSh2"+id+"' "+concSh+"><span id='conc"+id+"'>"+info[22]+"</span></td>";

  addStr += "<td id='ppSh1"+id+"' "+ppSh+"><image width=20 height=20 src='../images/miscellaneous/pp.gif' title='Защита от Яда'></td><td nowrap id='ppSh2"+id+"' "+ppSh+"><span id='pp"+id+"'>"+info[23]+"</span></td>";

  addStr += "<td id='m_protSh1"+id+"' "+protSh+"><image width=20 height=20 src='../images/miscellaneous/m_prot.gif' title='Сила Эффектов'></td><td nowrap id='m_protSh2"+id+"' "+protSh+"><span id='m_prot"+id+"'>"+info[24]+"</span></td>";

  addStr += "<td id='pnSh1"+id+"' "+pnSh+"><image width=20 height=20 src='../images/miscellaneous/pn.gif' title='Защита от Нейтральных Заклятий'></td><td nowrap id='pnSh2"+id+"' "+pnSh+"><span id='pn"+id+"'>"+info[27]+"</span></td>";


  var effectListText = getEffectsListText(info[18][0]) + info[18][1];

  statDiv.innerHTML = "<table><tr><td width=70 style='vertical-align: top;'><table><tr><td><image WIDTH=20 HEIGHT=20 src='../images/miscellaneous/attack_d.gif' title='Атака Драконов'>&nbsp;<image WIDTH=20 HEIGHT=20 src='../images/miscellaneous/defence_d.gif' title='Защита от Драконов'></td><td nowrap><span id='drak"+id+"'>"+info[6]+"/"+info[7]+"</span></td></tr><tr><td><image WIDTH=20 HEIGHT=20 src='../images/miscellaneous/attack_k.gif' title='Атака Рыцарей'>&nbsp;<image WIDTH=20 HEIGHT=20 src='../images/miscellaneous/defence_k.gif' title='Защита от Рыцарей'></td><td nowrap><span id='ric"+id+"'>"+info[8]+"/"+info[9]+"</span></td></tr><tr><td><image WIDTH=20 HEIGHT=20 src='../images/miscellaneous/attack_l.gif' title='Атака Дам'>&nbsp;<image WIDTH=20 HEIGHT=20 src='../images/miscellaneous/defence_l.gif' title='Защита от Дам'></td><td nowrap><span id='dam"+id+"'>"+info[10]+"/"+info[11]+"</span></td></tr></table></td><td width=70><table><tr><td><image WIDTH=20 HEIGHT=20 src='../images/miscellaneous/attack_c.gif' title='Атака Хаоса'>&nbsp;<image WIDTH=20 HEIGHT=20 src='../images/miscellaneous/defence_c.gif' title='Защита от Хаоса'></td><td nowrap><span id='haos"+id+"'>"+info[12]+"/"+info[13]+"</span></td></tr><tr><td><image WIDTH=20 HEIGHT=20 src='../images/miscellaneous/attack_h.gif' title='Атака Света'>&nbsp;<image WIDTH=20 HEIGHT=20 src='../images/miscellaneous/defence_h.gif' title='Защита от Света'></td><td nowrap><span id='svet"+id+"'>"+info[14]+"/"+info[15]+"</span></td></tr><tr><td><image WIDTH=20 HEIGHT=20 src='../images/miscellaneous/attack_s.gif' title='Атака Колдовства'>&nbsp;<image WIDTH=20 HEIGHT=20 src='../images/miscellaneous/defence_s.gif' title='Защита от Колдовства'></td><td nowrap><span id='kold"+id+"'>"+info[16]+"/"+info[17]+"</span></td></tr><tr id='astralSh"+id+"' "+astralSh+"><td><image WIDTH=20 HEIGHT=20 src='../images/miscellaneous/attack_a.gif' title='Атака Астрала'>&nbsp;<image WIDTH=20 HEIGHT=20 src='../images/miscellaneous/defence_a.gif' title='Защита от Астрала'></td><td nowrap><span id='astrl"+id+"'>"+info[25]+"/"+info[26]+"</span></td></tr></table></td><td><div id='eff"+id+"'>"+effectListText+"</div></td></tr><tr><td colspan=3><table><tr>"+addStr+"</tr></table></td></tr></table>";

  this.div.appendChild(statDiv);


function getEffectsListText(info) {
  var effectListText = "";
  var effectList = [];

  for (var i = 0; i < info.length; i++) {
    var effect = info[i];

    var turnsLeft = [];
    for (var j = 2; j < effect.length; j++) {
      turnsLeft.push(effect[j])
    }

    turnsLeft.sort(function (a, b) { return a - b })

    effectList.push([effect[0], effect[1], turnsLeft, turnsLeft[turnsLeft.length - 1]])
  }

  effectList.sort(function (a, b) { return b[3] - a[3] })

  for (i = 0; i < effectList.length; i++) {
    effect = effectList[i];

    var countText = ''
    if (effect[2].length > 1) {
      countText = 'x' + effect[2].length
    }

    var leftText = ''
    if (effect[2][0] !== 100) {
      leftText = " (осталось ходов: " + (effect[2].join(',')) + ")"
    }

    effectListText += "<img height=20 width=20 src='../images/effects/"+effect[0]+"' title='"+(effect[1])+leftText+"'><span style='vertical-align: 5px'>"+countText+"</span><br>"
  }

  return effectListText
}


Player.prototype.Upd = function(info)
{
  // Здоровье
  var width = Math.floor(100 * (info[4] / info[5]));
  if (width < 0)
  {
    width = 0;
  }

  $('hp_col'+this.id).style.width=width+'px';

  if(width >= 67) $('hp_col'+this.id).className='hp_green';
  else if(width < 67 && width > 20) $('hp_col'+this.id).className='hp_yellow';
  else if(width <= 20) $('hp_col'+this.id).className='hp_red';

  var id = this.id;

  $('hp_val'+id).innerHTML = info[4]+"/"+info[5];

  $('drak'+id).innerHTML = info[6]+"/"+info[7];
  $('ric'+id).innerHTML = info[8]+"/"+info[9];
  $('dam'+id).innerHTML = info[10]+"/"+info[11];

  $('haos'+id).innerHTML = info[12]+"/"+info[13];
  $('svet'+id).innerHTML = info[14]+"/"+info[15];
  $('kold'+id).innerHTML = info[16]+"/"+info[17];
  $('eff'+id).innerHTML = getEffectsListText(info[18][0]) + info[18][1];

  if( info[25] != 0 || info[26] != 0 ) {
    $('astrl'+id).innerHTML = info[25]+"/"+info[26];
    $('astralSh'+id).style.display = '';
  }
  else {
    $('astralSh'+id).style.display = 'none';
  }

  var tmpname=["luck", "reg", "learn", "conc", "pp", "m_prot", "", "", "pn"];
  for (var i=0, len=tmpname.length; i<len; i++)
  {
    if(i == 6 || i == 7) continue;
    if (info[i+19] != 0)
    {
      if (this.info[i+19]==0)
      {
        $(tmpname[i]+'Sh1'+id).style.display = '';
        $(tmpname[i]+'Sh2'+id).style.display = '';
      }
      $(tmpname[i]+id).innerHTML = info[i+19];
    }
    else
    {
      $(tmpname[i]+'Sh1'+id).style.display = 'none';
      $(tmpname[i]+'Sh2'+id).style.display = 'none';
    }
  }

  this.info = info;
}
*/