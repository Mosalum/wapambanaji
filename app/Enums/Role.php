<?php

namespace App\Enums;

enum Role: string
{
    case SUPER_ADMIN = 'SUPER_ADMIN';
    case GROUP_ADMIN = 'GROUP_ADMIN';
    case TREASURER = 'TREASURER';
    case SECRETARY = 'SECRETARY';
    case LOAN_OFFICER = 'LOAN_OFFICER';
    case AUDITOR = 'AUDITOR';
    case MEMBER = 'MEMBER';
}
