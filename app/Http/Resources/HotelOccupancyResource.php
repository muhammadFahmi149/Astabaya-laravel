<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelOccupancyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'year' => $this->year,
        ];

        // For combined data (monthly)
        if (isset($this->month)) {
            $data['month'] = $this->month;
        }

        // Common fields
        $data['mktj'] = $this->mktj !== null ? (float) $this->mktj : null;
        $data['tpk'] = $this->tpk !== null ? (float) $this->tpk : null;
        $data['rlmta'] = $this->rlmta !== null ? (float) $this->rlmta : null;
        $data['rlmtnus'] = $this->rlmtnus !== null ? (float) $this->rlmtnus : null;
        $data['rlmtgab'] = $this->rlmtgab !== null ? (float) $this->rlmtgab : null;
        $data['gpr'] = $this->gpr !== null ? (float) $this->gpr : null;

        return $data;
    }
}

