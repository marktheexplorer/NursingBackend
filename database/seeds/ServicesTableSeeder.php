<?php

use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->delete();

        DB::table('services')->insert([
        	[
        		'title' => 'Health Checks',
        		'description' => 'High Blood Pressure, Smoking, Diabetes, Gender and Raised Cholesterol are all risk factors in developing heart disease or strokes. We encourage everyone aged 45 and over to have their blood pressure checked every 5 years. Raised blood pressure has no symptoms making it difficult to confirm, please don’t be alarmed if we keep asking you back for review. Diabetes is very common; perhaps 4 in every 100 people suffer and it is more common if you are overweight.'
        	],[
        		'title' => 'Hypertension and CHD (Coronary Heart Disease)',
        		'description' => 'Provides support to those patients who suffer with High Blood Pressure and Heart Problems.  These Clinics provide support to patients in the management of their condition, regular monitoring and testing, advice, diet and healthy lifestyle'
        	],[
        		'title' => 'Diabetes',
        		'description' => 'Review and monitoring is offered for those patients who suffer from Diabetes, both Insulin and Non-Insulin Dependant.  The Clinic supports patients in the management of their condition together with advice on lifestyle, diet, weight and glucose testing.'
        	],[
        		'title' => 'COPD (Chronic Obstructive Pulmonary Disease)',
        		'description' => 'Provides support to patients who suffer Respiratory Problems such as Chronic Bronchitis and Emphysema.  The Clinic provides patients with regular review of their condition, measurement of respiratory flow, smoking advice and an assessment of treatment.'
        	],[
        		'title' => 'Baby Vaccination and Immunisation',
        		'description' => 'Provide valuable inoculation against disease to babies and children under the age of five.'
        	],
        ]);
    }
}
