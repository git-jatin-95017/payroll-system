@extends('layouts.app')

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
            <i class="fa fa-braille" style="color:#1976d2"></i>
            Run Payroll
        </h3>
    </div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="breadcrumb-item active">Run Payroll</li>
			<li class="breadcrumb-item active">/</li>
        </ol>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <table border="1">
            <tr>
                <td style="width: 7%;">
                    NUMBER
                </td>
                <td style="width: 14%;">
                    NAMES OF EMPLOYEES
                </td>
                <td style="width: 4%;">
                    <span class="d-block">S</span>
                    <span class="d-block">E</span>
                    <span class="d-block">X</span>
                </td>
                <td style="width: 35%;">
                    <table class="inner-table">
                        <tbody>
                            <tr>
                                <td colspan="5" class="table-top-none table-right-none table-left-none">EARNINGS AND CONTRIBUTIONS</td>
                            </tr>
                            <tr>
                                <td style="width: 18%;"class="table-left-none">W/E</td>
                                <td style="width: 18%;">W/E</td>
                                <td style="width: 18%;">W/E</td>
                                <td style="width: 18%;">W/E</td>
                                <td  class="table-right-none">W/E OR MONTHLY SALARY</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="table-bottom-none table-right-none table-left-none">-------</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 7%;">
                    <span class="d-block">TOTAL</span>
                    <span class="d-block">EARNINGS</span>
                    <span class="d-block">FOR MONTH</span>
                </td>
                <td style="width: 7%;">
                    <span class="d-block">TOTAL</span>
                    <span class="d-block">15%</span>
                    <span class="d-block">CONTR-IBUTIONS</span>
                </td>
                <td style="width: 7%;">
                    <span class="d-block">Number of</span>
                    <span class="d-block">Weeks</span>
                    <span class="d-block">Worked</span>
                </td>
                <td style="width: 4%;">
                    <span class="d-block">M</span>
                    <span class="d-block">F</span>
                    <span class="d-block">W</span>
                </td>
                <td>
                    Comment
                </td>
            </tr>
            <tr>
                <td style="width: 7%;">34532</td>
                <td style="width: 14%;">Donald O'Connell</td>
                <td style="width: 4%;">
                    <select name="" id="">
                        <option value="#">M</option>
                        <option value="#">F</option>
                    </select>
                </td>
                <td style="width: 35%;">
                    <table class="inner-table">
                        <tr>
                            <td style="width: 18%;" class="table-top-none table-left-none">-</td>
                            <td style="width: 18%;" class="table-top-none">-</td>
                            <td style="width: 18%;" class="table-top-none">-</td>
                            <td style="width: 18%;" class="table-top-none">-</td>
                            <td class="table-top-none table-right-none">7500</td>
                        </tr>
                        <tr>
                            <td class="table-left-none">-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td class="table-right-none">422</td>
                        </tr>
                        <tr>
                            <td class="table-bottom-none table-left-none">-</td>
                            <td class="table-bottom-none">-</td>
                            <td class="table-bottom-none">-</td>
                            <td class="table-bottom-none">-</td>
                            <td class="table-bottom-none table-right-none">522</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 7%;">
                    <table class="inner-table">
                        <tr>
                            <td class="table-right-none table-left-none table-top-none">--</td>
                        </tr>
                        <tr>
                            <td class="table-right-none table-left-none">7500</td>
                        </tr>
                        <tr>
                            <td class="table-right-none table-left-none table-bottom-none">--</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 7%;">
                    <table class="inner-table">
                        <tr>
                            <td class="table-right-none table-left-none table-top-none">--</td>
                        </tr>
                        <tr>
                            <td class="table-right-none table-left-none">975</td>
                        </tr>
                        <tr>
                            <td class="table-right-none table-left-none table-bottom-none">--</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 7%;">
                    <table class="inner-table">
                        <tr>
                            <td class="table-right-none table-left-none table-top-none">--</td>
                        </tr>
                        <tr>
                            <td class="table-right-none table-left-none">4</td>
                        </tr>
                        <tr>
                            <td class="table-right-none table-left-none table-bottom-none">--</td>
                        </tr>
                    </table>
                </td>   
                <td style="width: 4%;">
                    <select name="" id="">
                        <option value="#">M</option>
                        <option value="#">F</option>
                    </select>
                </td>
                <td>
                    ---
                </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="8" style="background-color: #777; color: #fff;">DO NOT WRITE ON THIS LINE</td>
            </tr>
        </table>
    </div>
</div>
@endsection

@push('page_scripts')
	<script>
		window.addEventListener('load', function () {
			setTimeout(function() {
		  		document.getElementById("confirm").click();
		  	}, 2000);
		});
	</script>


<style>

/* .content{
	position: relative;
} */

.img-load {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background-color: rgba(0,0,0, 0.4);
	z-index: 100;
}

.img-load div{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}
table{
    width: 100%;
    text-align: center;
    font-size: 12px;
    color: #252525;
    font-weight: 500;
    border-collapse: collapse;
}
table td{
    border: 1px solid #777;
}
.table-top-none{
    border-top: none;
}
.table-bottom-none{
    border-bottom: none;
}
.table-left-none{
    border-left: none;
}
.table-right-none{
    border-right: none;
}
</style>
@endpush