<?php

namespace App\Helpers;



class CareerMobilityHelpers
{
    public static function getSubMenus()
    {
        return [
            [
                'icon' => 'icon-clipboard6',
                'name' => 'Appointment',
                'route' => route('employee.create'),
                'assign_role' => 'employee.create',
                'active' => 'employee.create'
            ],
            // [
            //     'icon' => 'icon-clipboard6',
            //     'name' => 'Appointment',
            //     'route' => route('employee.careerMobilityAppointment.index'),
            //     'assign_role' => 'employee.careerMobilityAppointment.index',
            //     'active' => 'employee.careerMobilityAppointment.index'
            // ],
            [
                'icon' => 'icon-clipboard',
                'name' => 'Extension of Probationary/Contract Period',
                'route' => route('employee.careerMobilityExtensionOfProbationaryPeriod.index'),
                'assign_role' => 'employee.careerMobilityExtensionOfProbationaryPeriod.index',
                'active' => 'employee.careerMobilityExtensionOfProbationaryPeriod.index'
            ],
            [
                'icon' => 'icon-new-tab2',
                'name' => 'Confirmation',
                'route' =>  route('employee.careerMobilityConfirmation.index'),
                'assign_role' => 'employee.careerMobilityConfirmation.index',
                'active' => 'employee.careerMobilityConfirmation.index'
            ],
            [
                'icon' => 'icon-file-spreadsheet',
                'name' => 'Transfer',
                'route' => route('employee.careerMobilityTransfer.index'),
                'assign_role' => 'employee.careerMobilityTransfer.index',
                'active' => 'employee.careerMobilityTransfer.index'
            ],
            [
                'icon' => 'icon-floppy-disks',
                'name' => 'Temporary Transfer/Deputation',
                'route' => route('employee.careerMobilityTemporaryTransfer.index'),
                'assign_role' => 'employee.careerMobilityTemporaryTransfer.index',
                'active' => 'employee.careerMobilityTemporaryTransfer.index'
            ],
            [
                'icon' => 'icon-archive',
                'name' => 'Promotion',
                'route' => route('employee.careerMobilityPromotion.index'),
                'assign_role' => 'employee.careerMobilityPromotion.index',
                'active' => 'employee.careerMobilityPromotion.index'
            ],

            [
                'icon' => 'icon-wallet',
                'name' => 'Demotion',
                'route' => route('employee.careerMobilityDemotion.index'),
                'assign_role' => 'employee.careerMobilityDemotion.index',
                'active' => 'employee.careerMobilityDemotion.index'
            ],

            // [
            //     'icon' => 'icon-box',
            //     'name' => 'Temporary Assignment',
            //     'route' => '#',
            //     'assign_role' => 'employee.temporaryAssignment',
            // ],
            // [
            //     'icon' => 'icon-keyboard',
            //     'name' => 'Disciplinary',
            //     'route' => '#',
            //     'assign_role' => 'employee.disciplinary',
            // ],
            // [
            //     'icon' => 'icon-cabinet',
            //     'name' => 'Acting Appointment',
            //     'route' => '#',
            //     'assign_role' => 'employee.actingAppointment',
            // ],
            // [
            //     'icon' => 'icon-floppy-disks',
            //     'name' => 'Officiating Appointment',
            //     'route' => '#',
            //     'assign_role' => 'employee.officiatingAppointment',
            // ],
            // [
            //     'icon' => 'icon-drawer3',
            //     'name' => 'Resignation',
            //     'route' => '#',
            //     'assign_role' => 'employee.resignation',
            // ],
            // [
            //     'icon' => 'icon-tree7',
            //     'name' => 'Retirement',
            //     'route' => '#',
            //     'assign_role' => 'employee.retirement',
            // ],
            // [
            //     'icon' => 'icon-menu3',
            //     'name' => 'Leave Without Pay',
            //     'route' => '#',
            //     'assign_role' => 'employee.leaveWithoutPay',
            // ],
            // [
            //     'icon' => 'icon-paragraph-justify3',
            //     'name' => 'Renewal - Contract, Probation',
            //     'route' => '#',
            //     'assign_role' => 'employee.renewalContractProbation',
            // ],
            // [
            //     'icon' => 'icon-markup',
            //     'name' => 'Termination of Contract',
            //     'route' => '#',
            //     'assign_role' => 'employee.terminationOfContract',
            // ],

        ];
    }
}