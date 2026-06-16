<?php

namespace App\Services\Hero;

use App\Models\HeroMembership;
use App\Models\MembershipBenefitRule;

class HeroMembershipService
{
    public function validateMember(string $code, string $name, ?string $email = null, ?string $phone = null): array
    {
        $membership = HeroMembership::query()
            ->where('member_code', $code)
            ->where('is_active', true)
            ->first();

        if (! $membership || ! $membership->isValid()) {
            return ['valid' => false, 'benefits' => []];
        }

        $normalizedName = strtolower(trim($name));
        $allowedNames = collect([$membership->member_name])
            ->merge($membership->covered_members ?? [])
            ->map(fn ($n) => strtolower(trim($n)));

        if (! $allowedNames->contains($normalizedName)) {
            return ['valid' => false, 'benefits' => [], 'reason' => 'Passenger name does not match membership'];
        }

        if ($email && $membership->email && strcasecmp($membership->email, $email) !== 0) {
            return ['valid' => false, 'benefits' => [], 'reason' => 'Email does not match membership'];
        }

        $benefits = MembershipBenefitRule::query()
            ->where('plan_level', $membership->plan_level)
            ->where('is_active', true)
            ->get();

        return [
            'valid' => true,
            'plan_level' => $membership->plan_level,
            'benefits' => $benefits->toArray(),
        ];
    }
}
