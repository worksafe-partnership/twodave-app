<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Company::class, function (Faker $faker) {
    $config = Config('egc');

    return [
        'name' => $faker->company,
        'review_timescale' => $faker->randomElement(array_flip($config['review_timescales'])),
        'vtrams_name' => 'TBC',
        'email' => $faker->safeEmail,
        'phone' => 'TBC',
        'fax' => 'TBC',
        'low_risk_character' => 'L',
        'med_risk_character' => 'M',
        'high_risk_character' => 'H',
        'no_risk_character' => '#',
        'primary_colour' => $faker->hexcolor,
        'secondary_colour' => $faker->hexcolor,
        'light_text' => $faker->randomElement([null, 1]),
        'accept_label' => 'Accept Label Test',
        'amend_label' => 'Amend Label Test',
        'reject_label' => 'Reject Label Test',
    ];
});

$factory->define(App\User::class, function (Faker $faker) {
    // $config = Config('egc');
    return [
        'name' => $faker->name,
        'company_id' => 1,
        'email' => $faker->safeEmail,
        'password' => 'aaaaaaa'
    ];
});

$factory->define(App\Project::class, function (Faker $faker) {
    $config = Config('egc');

    return [
        'name' => $faker->words(3, true),
        'ref' => $faker->randomNumber,
        'company_id' => 1, // should always be overridden
        'project_admin' => 1, // should always be overridden
        'principle_contractor' => 1, // should always be overridden
        'principle_contractor_name' => $faker->name,
        'principle_contractor_email' => $faker->safeEmail,
        'client_name' => $faker->name,
        'review_timescale' => $faker->randomElement(array_flip($config['review_timescales'])),
        'show_contact' => $faker->randomElement([null, 1]),
    ];
});

$factory->define(App\Vtram::class, function (Faker $faker) {
    $config = Config('egc');

    return [
        'company_id' => 1,
        'project_id' => 1,
        'name' => $faker->words(3, true),
        'description' => $faker->words(3, true),
        // 'logo',
        'reference' => $faker->words(2, true),
        'key_points' => "<p>".$faker->words(3, true)."</p>",
        // 'havs_noise_assessment',
        // 'coshh_assessment',
        'review_due' => $faker->dateTimeBetween('now', '+1 months', null),
        // 'approved_date',
        // 'original_id',
        // 'revision_number',
        'status' => $faker->randomElement(['NEW', 'PENDING', 'REJECTED', 'EXTERNAL_REJECT']),
        // 'created_by',
        // 'updated_by',
        // 'submitted_by',
        // 'approved_by',
        // 'date_replaced',
        // 'resubmit_by',
        // 'pre_risk_assessment_text',
        // 'post_risk_assessment_text',
        // 'dynamic_risk',
        // 'pdf',
        // 'pages_in_pdf',
        // 'created_from',
        // 'show_responsible_person',
        // 'responsible_person'
    ];
});
