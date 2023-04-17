<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

enum CourseModality: string {

    use InvokableCases, Values;

    case FaceToFace = 'faceToFace';
    case OnlineLive = 'onlineLive';
    case OnlineRecorded = 'onlineRecorded';
    case Hybrid = 'hybrid';
}
