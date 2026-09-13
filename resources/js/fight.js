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

/*
var allinfo=[
[[125569, 'Росомаха', 'M', 6, 117, 117, 0, 15, 0, 15, 0, 15, 8, 8, 0, 0, 0, 0, [[], ""], 5, 0, 0, 4, 0, 0,
     0, 0, 0, 0]], [1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [[1301, 1, 1], [1401, 2, 1], [1601, 30, 1], [1602, 30, 1], [1664, 6, 1], [1696, 10, 1], [2566, 10, 2], [2577, 6, 2], [2601, 30, 2], [2602, 30, 2], [2606, 1, 2], [2612, 10, 2], [2664, 5, 2], [3601, 30, 3], [3602, 30, 3], [3664, 10, 3]]];

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
*/