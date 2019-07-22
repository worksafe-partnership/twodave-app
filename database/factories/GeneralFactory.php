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
        'main_description' => '<p>'.$faker->words(20, true).'</p>',
        'post_risk_assessment_text' => '<p>'.$faker->words(20, true).'</p>',
        'task_description' => '<p>'.$faker->words(20, true).'</p>',
        'plant_and_equipment' => '<p>'.$faker->words(20, true).'</p>',
        'disposing_of_waste' => '<p>'.$faker->words(20, true).'</p>',
        'first_aid' => '<p>'.$faker->words(20, true).'</p>',
        'noise' => '<p>'.$faker->words(20, true).'</p>',
        'working_at_height' => '<p>'.$faker->words(20, true).'</p>',
        'manual_handling' => '<p>'.$faker->words(20, true).'</p>',
        'accident_reporting' => '<p>'.$faker->words(20, true).'</p>',
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
    $args = func_get_args()[1];

    $nextNumber = \App\NextNumber::where('company_id', $args['company_id'])->first();
    if ($nextNumber) {
        $number = $nextNumber->number;
        $number++;
        $nextNumber->update(['number' => $number]);
    } else {
        $number = 1;
        \App\NextNumber::insert(['company_id' => $args['company_id'], 'number' => $number]);
    }

    return [
        'company_id' => 1,
        'project_id' => 1,
        'name' => $faker->words(3, true),
        'description' => $faker->words(3, true),
        // 'logo',
        'reference' => $faker->words(2, true),
        'key_points' => "<p>".$faker->words(50, true)."</p>",
        // 'havs_noise_assessment',
        // 'coshh_assessment',
        'review_due' => $faker->dateTimeBetween('now', '+1 months', null),
        // 'approved_date',
        // 'original_id',
        // 'revision_number',
        'status' => $faker->randomElement(['NEW', 'PENDING', 'REJECTED', 'EXTERNAL_REJECT', 'CURRENT']),
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
        'number' => $number
    ];
});

$factory->define(App\Briefing::class, function (Faker $faker) {
    $config = Config('egc');

    return [
        'project_id' => 1,
        'vtram_id' => 1,
        'briefed_by' => 'seeded name',
        'name' => 'Briefing '.$faker->numberBetween(0, 100),
        'notes' => '<p>'.$faker->words(50, true).'</p>'
    ];
});


$factory->define(App\Template::class, function (Faker $faker) {
    return [
        'company_id' => 1,
        'name' => 'Seeded Template '.$faker->numberBetween(0, 100),
        'description' => 'Seeded Description',
        // 'logo',
        'reference' => 'Seeded Reference',
        'key_points' => "<p>".$faker->words(50, true)."</p>",
        // 'havs_noise_assessment',
        // 'coshh_assessment',
        // 'review_due',
        // 'approved_date',
        // 'original_id',
        // 'revision_number',
        'status' => 'NEW',
        'created_by' => 1,
        // 'updated_by',
        // 'submitted_by' => 1,
        // 'approved_by',
        // 'date_replaced',
        // 'resubmit_by'
    ];
});

$factory->define(App\Hazard::class, function (Faker $faker) {
    $config = Config('egc');
    $args = func_get_args()[1];

    $listOrder = \App\Hazard::where('entity', $args['entity'])
                          ->where('entity_id', $args['entity_id'])
                          ->max('list_order');

    if (is_null($listOrder)) {
        $listOrder = 1;
    } else {
        $listOrder++;
    }

    $numberOfWords = $faker->numberBetween(3, 20);
    return [
        'description' => $faker->words($numberOfWords, true),
        // 'entity' => 'VTRAM',
        // 'entity_id' => 1,
        'control' => $faker->words($numberOfWords, true),
        // // 'risk',
        'risk_probability' => $faker->numberBetween(1, 5),
        'risk_severity' => $faker->numberBetween(1, 5),
        // 'r_risk',
        'r_risk_probability' => $faker->numberBetween(1, 5),
        'r_risk_severity' => $faker->numberBetween(1, 5),
        'list_order' => $listOrder,
        'at_risk' => $faker->randomElement(array_flip($config['hazard_who_risk'])),
        // 'other_at_risk'
    ];
});

$factory->define(App\Methodology::class, function (Faker $faker) {
    $config = Config('egc');
    $categories = array_keys($config['methodology_categories']);
    $args = func_get_args()[1];
    $numberOfWords = $faker->numberBetween(3, 20);

    $listOrder = \App\Methodology::where('entity', $args['entity'])
                          ->where('entity_id', $args['entity_id'])
                          ->max('list_order');

    if (is_null($listOrder)) {
        $listOrder = 1;
    } else {
        $listOrder++;
    }

    return [
        'category' => $faker->randomElement($categories),
        'entity' => 'TEST',
        'entity_id' => 1,
        'text_before' => $faker->words($numberOfWords, true),
        'text_after' => $faker->words($numberOfWords, true),
        // 'image' => 1,
        // 'image_on' => 1,
        'list_order' => $listOrder
    ];
});
