# Create Multilingual Migration

Generate a Laravel migration with proper multilingual column support for the Silk Way ru/kz/cn stack.

**Description:** $ARGUMENTS  
Format: `table_name column1 column2 ...` (e.g., `routes departure_city arrival_city description`)

## Context

- Multilingual text columns use triple suffix: `{column}_rus`, `{column}_kaz`, `{column}_chn`
- This pattern is used in: cargo, cars, cities tables — follow the same convention
- Status columns use enum
- All tables get `id()`, `timestamps()`

## Steps

1. **Generate migration file**:
   ```
   php artisan make:migration create_{table}_table
   ```
   Or `add_{columns}_to_{table}_table` for adding columns.

2. **Write the Schema** following this pattern:
   ```php
   Schema::create('table_name', function (Blueprint $table) {
       $table->id();
       
       // Multilingual text columns
       $table->string('departure_city_rus')->nullable();
       $table->string('departure_city_kaz')->nullable();
       $table->string('departure_city_chn')->nullable();
       
       $table->string('arrival_city_rus')->nullable();
       $table->string('arrival_city_kaz')->nullable();
       $table->string('arrival_city_chn')->nullable();
       
       $table->text('description_rus')->nullable();
       $table->text('description_kaz')->nullable();
       $table->text('description_chn')->nullable();
       
       // Use text() for longer content, string() for short labels
       
       $table->timestamps();
   });
   ```

3. **Add foreign keys** with proper constraints:
   ```php
   $table->foreignId('user_id')->constrained()->cascadeOnDelete();
   $table->foreignId('cargo_id')->constrained('cargo')->cascadeOnDelete();
   ```

4. **Add status enum** if applicable:
   ```php
   $table->enum('status', ['pending', 'active', 'completed'])->default('pending');
   ```

5. **Create the Model** with multilingual accessor pattern (from City model):
   ```php
   public function getLocalizedNameAttribute(): string
   {
       $locale = app()->getLocale(); // 'ru', 'kz', 'cn'
       $suffix = match($locale) {
           'kz' => 'kaz',
           'cn' => 'chn',
           default => 'rus',
       };
       return $this->{"name_{$suffix}"} ?? $this->name_rus ?? '';
   }
   ```
   Apply this pattern to each multilingual field group.

6. **Run migration**: `php artisan migrate`
