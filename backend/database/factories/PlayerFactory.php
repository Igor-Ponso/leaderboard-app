<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Lista de endereços reais e coerentes do Canadá para testes
     *
     * @var array<int, array<string, string>>
     */
    private array $sampleCanadianAddresses = [
        [
            'postal_code' => 'V6C 1A1',
            'street' => '999 Canada Place',
            'city' => 'Vancouver',
            'province' => 'BC',
        ],
        [
            'postal_code' => 'M5H 2N2',
            'street' => '100 Queen St W',
            'city' => 'Toronto',
            'province' => 'ON',
        ],
        [
            'postal_code' => 'T5J 2R7',
            'street' => '1 Sir Winston Churchill Square',
            'city' => 'Edmonton',
            'province' => 'AB',
        ],
        [
            'postal_code' => 'G1R 5M1',
            'street' => '85 Rue Dalhousie',
            'city' => 'Québec',
            'province' => 'QC',
        ],
        [
            'postal_code' => 'B3J 3K5',
            'street' => '1800 Argyle St',
            'city' => 'Halifax',
            'province' => 'NS',
        ],
        [
            'postal_code' => 'R3C 1A5',
            'street' => '510 Main St',
            'city' => 'Winnipeg',
            'province' => 'MB',
        ],
        [
            'postal_code' => 'S4P 3Y2',
            'street' => '2311 12th Ave',
            'city' => 'Regina',
            'province' => 'SK',
        ],
        [
            'postal_code' => 'E1C 1G2',
            'street' => '655 Main St',
            'city' => 'Moncton',
            'province' => 'NB',
        ],
        [
            'postal_code' => 'C1A 7N8',
            'street' => '165 Richmond St',
            'city' => 'Charlottetown',
            'province' => 'PE',
        ],
        [
            'postal_code' => 'X1A 2N2',
            'street' => '4807 49th Ave',
            'city' => 'Yellowknife',
            'province' => 'NT',
        ],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $address = $this->faker->randomElement($this->sampleCanadianAddresses);

        return [
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date(),
            'score' => 0,
            'address' => $address,
        ];
    }
}
