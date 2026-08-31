<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\ContractType;
use App\Models\AbsenceType;
use App\Models\AbsenceRequest;
use App\Models\PayrollLineType;
use App\Models\LeaveBalance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PayrollModuleSeeder extends Seeder
{
    public function run(): void
    {
        // On sécurise l'insertion en désactivant temporairement les contraintes de clés étrangères
        Schema::disableForeignKeyConstraints();
        
        // Nettoyage des tables pour éviter les doublons lors des lancements successifs
        DB::table('classification_payroll_line_type')->truncate();
        DB::table('payslip_lines')->truncate();
        DB::table('payslips')->truncate();
        DB::table('contracts')->truncate();
        DB::table('contract_types')->truncate();
        DB::table('absence_requests')->truncate();
        DB::table('absence_types')->truncate();
        DB::table('leave_balances')->truncate();

        // 1. Création des Types de Contrats fondamentaux
        $cdi = ContractType::create([
            'code' => 'CDI',
            'label' => 'Contrat à Durée Indéterminée',
            'is_fixed_term' => false
        ]);

        $cdd = ContractType::create([
            'code' => 'CDD',
            'label' => 'Contrat à Durée Déterminée',
            'is_fixed_term' => true,
            'max_duration_months' => 12
        ]);

        // 2. Création des Types d'Absences standard
        $cp = AbsenceType::create([
            'code' => 'CP',
            'label' => 'Congés Payés',
            'is_paid' => true,
            'is_cumulative' => true
        ]);

        $css = AbsenceType::create([
            'code' => 'CSS',
            'label' => 'Congé Sans Solde',
            'is_paid' => false,
            'is_cumulative' => false
        ]);

        // 3. Récupération d'un employé existant dans votre base de données
        // (Le groupe ayant déjà migré et peuplé la table employees)
        $employeeId = DB::table('employees')->value('id');

        if (!$employeeId) {
            // Sécurité : Si la table de votre groupe est vide pour le moment, on force un ID temporaire à 1
            $employeeId = 1;
        }

        // 4. Attribution d'un contrat actif à cet employé (Salaire de base : 3000 €)
        $contract = Contract::create([
            'employee_id' => $employeeId,
            'contract_type_id' => $cdi->id,
            'start_date' => '2026-01-01',
            'pay_frequency' => 'Monthly',
            'base_salary' => 3000.00,
            'status' => 'Active'
        ]);

        // 5. Initialisation d'un solde de congés pour l'employé
        LeaveBalance::create([
            'employee_id' => $employeeId,
            'absence_type_id' => $cp->id,
            'year' => 2026,
            'cumulative_acquired_days' => 30.00,
            'consumed_days' => 0.00,
            'expired_days' => 0.00,
            'available_balance' => 30.00
        ]);

        // 6. Simulation d'une demande d'absence APPROUVÉE et DÉDUCTIBLE de 3 jours en août 2026
        // Règle de calcul attendue : 3 jours d'absence = Retenue de (3000 / 30) * 3 = 300 €
        AbsenceRequest::create([
            'employee_id' => $employeeId,
            'absence_type_id' => $css->id, // Sans solde donc déductible du salaire
            'requested_start_date' => '2026-08-10',
            'requested_end_date' => '2026-08-12',
            'requested_days_count' => 3,
            'reason' => 'Déménagement personnel',
            'status' => 'Approved',
            'is_deductible' => true
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
