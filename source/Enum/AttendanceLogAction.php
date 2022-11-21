<?php

namespace Source\Enum; 

enum AttendanceLogAction : int {

    case CREATED_ATTENDANCE = 1;
    case UPDATED_TO_ACTIVE = 2;
    case UPDATED_TO_INACTIVE = 3;
    case UPDATED_TO_CLOSED = 4;
    case CREATED_FROM_TRANSFER = 5;
    case TRANSFERRED = 6;
}