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
        <div class="bg-white p-4 rounded mb-4">
            <div class="d-flex w-100 align-items-center mb-4">
                <div class="logo-tabled">
                    <img src="https://dynamic.brandcrowd.com/asset/logo/c5d02ad5-9111-4a7c-847f-39199bd91ac6/logo-search-grid-1x" alt="">
                </div>
                <div class="d-flex justify-content-center w-100">
                    <div class="text-center social-security-heading">
                        <h2>SOCIAL SECURITY ACT 1972</h2>
                        <p>MONTHLY REMITTANCE FORM</p>
                    </div>
                </div>
            </div>
           <div class="row mb-3">
                <div class="col-6">
                    <div class="d-flex">
                        <div class="mr-3">
                            <label class="custom-label-top mr-2 mb-0">EMPLOYER:</label>
                            <input type="text" class="custom-input-top">
                        </div>
                        <div>
                            <label class="custom-label-top mr-2 mb-0">Business name goes here</label>
                            <input type="text" class="custom-input-top">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex">
                        <div class="mr-3">
                            <label class="custom-label-top mr-2 mb-0">REGISTRATION NO.:</label>
                            <input type="text" class="custom-input-top">
                        </div>
                        <div>
                            <label class="custom-label-top mr-2 mb-0">Employer Number goes here</label>
                            <input type="text" class="custom-input-top">
                        </div>
                    </div>
                </div>  
           </div>
            <table>
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
                    <td style="width: 14%;"> 
                        <input type="text" class="cell-input" placeholder="Donald O'Connell">
                    </td>
                    <td style="width: 4%;">
                        <select name="" id="">
                            <option value="#">M</option>
                            <option value="#">F</option>
                        </select>
                    </td>
                    <td style="width: 35%;">
                        <table class="inner-table">
                            <tr>
                                <td style="width: 18%;" class="table-top-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-top-none table-right-none">
                                    <input type="text" class="cell-input" placeholder="7500">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-right-none">
                                    <input type="text" class="cell-input" placeholder="422">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-bottom-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none table-right-none">
                                    <input type="text" class="cell-input" placeholder="522">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="7500">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="975">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="4">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
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
                        <textarea class="cell-comment"></textarea>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="8" style="background-color: #777; color: #fff;">DO NOT WRITE ON THIS LINE</td>
                </tr>
                <tr>
                    <td style="width: 7%;">34532</td>
                    <td style="width: 14%;">
                        <input type="text" class="cell-input" placeholder="">
                    </td>
                    <td style="width: 4%;">
                        <select name="" id="">
                            <option value="#">M</option>
                            <option value="#">F</option>
                        </select>
                    </td>
                    <td style="width: 35%;">
                        <table class="inner-table">
                            <tr>
                                <td style="width: 18%;" class="table-top-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-top-none table-right-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-right-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-bottom-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none table-right-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="4">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
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
                        <textarea class="cell-comment"></textarea>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="8" style="background-color: #777; color: #fff;">DO NOT WRITE ON THIS LINE</td>
                </tr>
                <tr>
                    <td style="width: 7%;">34532</td>
                    <td style="width: 14%;">
                        <input type="text" class="cell-input" placeholder="">
                    </td>
                    <td style="width: 4%;">
                        <select name="" id="">
                            <option value="#">M</option>
                            <option value="#">F</option>
                        </select>
                    </td>
                    <td style="width: 35%;">
                        <table class="inner-table">
                            <tr>
                                <td style="width: 18%;" class="table-top-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-top-none table-right-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-right-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-bottom-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none table-right-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="4">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
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
                        <textarea class="cell-comment"></textarea>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="8" style="background-color: #777; color: #fff;">DO NOT WRITE ON THIS LINE</td>
                </tr>
                <tr>
                    <td style="width: 7%; border: none; padding: 5px;"></td>
                    <td colspan="2" style="border: none; padding: 5px;">
                    </td>
                    <td style="border: none; text-align: right; padding: 5px;">TOTALS</td>
                    <td style="padding: 5px;">-</td>
                    <td style="padding: 5px;">-</td>
                    <td style="border: none; padding: 5px;" colspan="3"></td>
                </tr>
                <tr>
                    <td style="width: 7%; border: none; padding: 5px;"></td>
                    <td colspan="2" style="border: none; padding: 5px;">
                        <div class="d-flex">
                            <div>
                                <label class="custom-label-top mr-2 mb-0">EMPLOYER:</label>
                                <input type="text" class="custom-input-top" spellcheck="false" data-ms-editor="true">
                            </div>
                        </div>
                    </td>
                    <td style="border: none; text-align: right; padding: 5px;">CARRIED</td>
                    <td style="padding: 5px;">7500</td>
                    <td style="padding: 5px;">975</td>
                    <td style="border: none; padding: 5px;" colspan="3"></td>
                </tr>
                <tr>
                    <td style="width: 7%; border: none; padding: 5px;"></td>
                    <td colspan="2" style="border: none; padding: 5px;">
                        <div class="d-flex">
                            <div>
                                <label class="custom-label-top mr-2 mb-0">Date:</label>
                                <input type="text" class="custom-input-top" spellcheck="false" data-ms-editor="true">
                            </div>
                        </div>
                    </td>
                    <td style="border: none; text-align: right; padding: 5px;">FORWARD</td>
                    <td style="padding: 5px;">-</td>
                    <td style="padding: 5px;">-</td>
                    <td style="border: none; padding: 5px;" colspan="3"></td>
                </tr>
            </table>
        </div>
        <!-- <div class="bg-white p-4 rounded mb-4">
            <div class="d-flex w-100 align-items-center mb-4">
                <div class="logo-tabled">
                    <img src="https://dynamic.brandcrowd.com/asset/logo/c5d02ad5-9111-4a7c-847f-39199bd91ac6/logo-search-grid-1x" alt="">
                </div>
                <div class="d-flex justify-content-center w-100">
                    <div class="text-center social-security-heading">
                        <h2>SOCIAL SECURITY ACT 1972</h2>
                        <p>MONTHLY REMITTANCE FORM</p>
                    </div>
                </div>
            </div>
           <div class="row mb-3">
                <div class="col-6">
                    <div class="d-flex">
                        <div class="mr-3">
                            <label class="custom-label-top mr-2 mb-0">EMPLOYER:</label>
                            <input type="text" class="custom-input-top">
                        </div>
                        <div>
                            <label class="custom-label-top mr-2 mb-0">Business name goes here</label>
                            <input type="text" class="custom-input-top">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex">
                        <div class="mr-3">
                            <label class="custom-label-top mr-2 mb-0">REGISTRATION NO.:</label>
                            <input type="text" class="custom-input-top">
                        </div>
                        <div>
                            <label class="custom-label-top mr-2 mb-0">Employer Number goes here</label>
                            <input type="text" class="custom-input-top">
                        </div>
                    </div>
                </div>  
           </div>
            <table>
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
                    <td style="width: 14%;"> 
                        <input type="text" class="cell-input" placeholder="Donald O'Connell">
                    </td>
                    <td style="width: 4%;">
                        <select name="" id="">
                            <option value="#">M</option>
                            <option value="#">F</option>
                        </select>
                    </td>
                    <td style="width: 35%;">
                        <table class="inner-table">
                            <tr>
                                <td style="width: 18%;" class="table-top-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-top-none table-right-none">
                                    <input type="text" class="cell-input" placeholder="7500">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-right-none">
                                    <input type="text" class="cell-input" placeholder="422">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-bottom-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none table-right-none">
                                    <input type="text" class="cell-input" placeholder="522">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="7500">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="975">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="4">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
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
                        <textarea class="cell-comment"></textarea>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="8" style="background-color: #777; color: #fff;">DO NOT WRITE ON THIS LINE</td>
                </tr>
                <tr>
                    <td style="width: 7%;">34532</td>
                    <td style="width: 14%;">
                        <input type="text" class="cell-input" placeholder="">
                    </td>
                    <td style="width: 4%;">
                        <select name="" id="">
                            <option value="#">M</option>
                            <option value="#">F</option>
                        </select>
                    </td>
                    <td style="width: 35%;">
                        <table class="inner-table">
                            <tr>
                                <td style="width: 18%;" class="table-top-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-top-none table-right-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-right-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-bottom-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none table-right-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="4">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
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
                        <textarea class="cell-comment"></textarea>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="8" style="background-color: #777; color: #fff;">DO NOT WRITE ON THIS LINE</td>
                </tr>
                <tr>
                    <td style="width: 7%;">34532</td>
                    <td style="width: 14%;">
                        <input type="text" class="cell-input" placeholder="">
                    </td>
                    <td style="width: 4%;">
                        <select name="" id="">
                            <option value="#">M</option>
                            <option value="#">F</option>
                        </select>
                    </td>
                    <td style="width: 35%;">
                        <table class="inner-table">
                            <tr>
                                <td style="width: 18%;" class="table-top-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td style="width: 18%;" class="table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-top-none table-right-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td>
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-right-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-bottom-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                                <td class="table-bottom-none table-right-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="0.00">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 7%;">
                        <table class="inner-table">
                            <tr>
                                <td class="table-right-none table-left-none table-top-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none">
                                    <input type="text" class="cell-input" placeholder="4">
                                </td>
                            </tr>
                            <tr>
                                <td class="table-right-none table-left-none table-bottom-none">
                                    <input type="text" class="cell-input" placeholder="-">
                                </td>
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
                        <textarea class="cell-comment"></textarea>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="8" style="background-color: #777; color: #fff;">DO NOT WRITE ON THIS LINE</td>
                </tr>
                <tr>
                    <td style="width: 7%; border: none; padding: 5px;"></td>
                    <td colspan="2" style="border: none; padding: 5px;">
                    </td>
                    <td style="border: none; text-align: right; padding: 5px;">TOTALS</td>
                    <td style="padding: 5px;">-</td>
                    <td style="padding: 5px;">-</td>
                    <td style="border: none; padding: 5px;" colspan="3"></td>
                </tr>
                <tr>
                    <td style="width: 7%; border: none; padding: 5px;"></td>
                    <td colspan="2" style="border: none; padding: 5px;">
                        <div class="d-flex">
                            <div>
                                <label class="custom-label-top mr-2 mb-0">EMPLOYER:</label>
                                <input type="text" class="custom-input-top" spellcheck="false" data-ms-editor="true">
                            </div>
                        </div>
                    </td>
                    <td style="border: none; text-align: right; padding: 5px;">CARRIED</td>
                    <td style="padding: 5px;">7500</td>
                    <td style="padding: 5px;">975</td>
                    <td style="border: none; padding: 5px;" colspan="3"></td>
                </tr>
                <tr>
                    <td style="width: 7%; border: none; padding: 5px;"></td>
                    <td colspan="2" style="border: none; padding: 5px;">
                        <div class="d-flex">
                            <div>
                                <label class="custom-label-top mr-2 mb-0">Date:</label>
                                <input type="text" class="custom-input-top" spellcheck="false" data-ms-editor="true">
                            </div>
                        </div>
                    </td>
                    <td style="border: none; text-align: right; padding: 5px;">FORWARD</td>
                    <td style="padding: 5px;">-</td>
                    <td style="padding: 5px;">-</td>
                    <td style="border: none; padding: 5px;" colspan="3"></td>
                </tr>
            </table>
        </div> -->
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