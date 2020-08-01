<table class="risk-table">
    <tr>
        <th rowspan="3" colspan="3" class="center padding-21 hide-report">RISK ASSESSMENT CALCULATION LEGEND</th>
        <th class="center" colspan="5">S = SEVERITY</th>
    </tr>
    <tr>
        <th class="center">1</th>
        <th class="center">2</th>
        <th class="center">3</th>
        <th class="center">4</th>
        <th class="center">5</th>
    </tr>
    <tr>
        <th class="center">NEGLIGIBLE</th>
        <th class="center">MINOR</th>
        <th class="center">MODERATE</th>
        <th class="center">MAJOR</th>
        <th class="center">EXTREME</th>
    </tr>
    <tr>
        <th rowspan="5" class="center padding-21">L = LIKELIHOOD</th>
        <th class="center small-pad">1</th>
        <th class="small-pad">RARE</th>
        <td class="green center risk-rating" data-value="1" data-type="{{ $hazardType }}" data-prob="1" data-severity="1">1</td>
        <td class="green center risk-rating" data-value="2" data-type="{{ $hazardType }}" data-prob="1" data-severity="2">2</td>
        <td class="green center risk-rating" data-value="3" data-type="{{ $hazardType }}" data-prob="1" data-severity="3">3</td>
        <td class="green center risk-rating" data-value="4" data-type="{{ $hazardType }}" data-prob="1" data-severity="4">4</td>
        <td class="amber center risk-rating" data-value="5" data-type="{{ $hazardType }}" data-prob="1" data-severity="5">5</td>
    </tr>
    <tr>
        <th class="center small-pad">2</th>
        <th class="small-pad">UNLIKELY</th>
        <td class="green center risk-rating" data-value="2" data-type="{{ $hazardType }}" data-prob="2" data-severity="1">2</td>
        <td class="green center risk-rating" data-value="4" data-type="{{ $hazardType }}" data-prob="2" data-severity="2">4</td>
        <td class="amber center risk-rating" data-value="6" data-type="{{ $hazardType }}" data-prob="2" data-severity="3">6</td>
        <td class="amber center risk-rating" data-value="8" data-type="{{ $hazardType }}" data-prob="2" data-severity="4">8</td>
        <td class="amber center risk-rating" data-value="10" data-type="{{ $hazardType }}" data-prob="2" data-severity="5">10</td>
    </tr>
    <tr>
        <th class="center small-pad">3</th>
        <th class="small-pad">POSSIBLE</th>
        <td class="green center risk-rating" data-value="3" data-type="{{ $hazardType }}" data-prob="3" data-severity="1">3</td>
        <td class="amber center risk-rating" data-value="6" data-type="{{ $hazardType }}" data-prob="3" data-severity="2">6</td>
        <td class="amber center risk-rating" data-value="9" data-type="{{ $hazardType }}" data-prob="3" data-severity="3">9</td>
        <td class="amber center risk-rating" data-value="12" data-type="{{ $hazardType }}" data-prob="3" data-severity="4">12</td>
        <td class="red center risk-rating" data-value="15" data-type="{{ $hazardType }}" data-prob="3" data-severity="5">15</td>
    </tr>
    <tr>
        <th class="center small-pad">4</th>
        <th class="small-pad">LIKELY</th>
        <td class="green center risk-rating" data-value="4" data-type="{{ $hazardType }}" data-prob="4" data-severity="1">4</td>
        <td class="amber center risk-rating" data-value="8" data-type="{{ $hazardType }}" data-prob="4" data-severity="2">8</td>
        <td class="amber center risk-rating" data-value="12" data-type="{{ $hazardType }}" data-prob="4" data-severity="3">12</td>
        <td class="red center risk-rating" data-value="16" data-type="{{ $hazardType }}" data-prob="4" data-severity="4">16</td>
        <td class="red center risk-rating" data-value="20" data-type="{{ $hazardType }}" data-prob="4" data-severity="5">20</td>
    </tr>
    <tr>
        <th class="center small-pad">5</th>
        <th class="small-pad">CERTAIN</th>
        <td class="amber center risk-rating" data-value="5" data-type="{{ $hazardType }}" data-prob="5" data-severity="1">5</td>
        <td class="amber center risk-rating" data-value="10" data-type="{{ $hazardType }}" data-prob="5" data-severity="2">10</td>
        <td class="red center risk-rating" data-value="15" data-type="{{ $hazardType }}" data-prob="5" data-severity="3">15</td>
        <td class="red center risk-rating" data-value="20" data-type="{{ $hazardType }}" data-prob="5" data-severity="4">20</td>
        <td class="red center risk-rating" data-value="25" data-type="{{ $hazardType }}" data-prob="5" data-severity="5">25</td>
    </tr>
</table>
