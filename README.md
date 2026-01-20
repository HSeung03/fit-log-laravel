# 🍎 HealthLog: 운동 기록 & 영양 관리 시스템

> 사용자가 자신의 식단과 운동량을 기록하고, 영양 성분을 체계적으로 모니터링할 수 있는 웹 애플리케이션입니다.

---

## 🛠 Tech Stack
- **Framework:** Laravel 10 (PHP 8.2)
- **Database:** MySQL
- **Tooling:** Eloquent ORM, Tinker, Seeder

## ✨ 주요 기능 (Key Functionalities)

### 1. 효율적인 식단 데이터 조회
- **Eloquent Query Builder**를 사용하여 수천 개의 음식 데이터 중 필요한 정보를 실시간으로 필터링합니다.
- `whereRaw` 등을 사용하여 복잡한 영양 성분 계산 조건을 처리했습니다.

### 2. 체계적인 음식 카테고리화
- **One-to-Many Relationship**을 통해 음식 종류(FoodType)를 구분하여 사용자가 식단을 직관적으로 관리할 수 있게 설계했습니다.
- 유연한 데이터 관리를 위해 숫자가 아닌 문자열 기반의 고유 식별자(PK)를 사용했습니다.

### 3. 데이터 일관성 및 자동화
- **Factory & Seeder**를 활용하여 테스트 환경에서도 실제와 유사한 데이터를 즉시 구축할 수 있도록 자동화했습니다.
- **Soft Deletes** 기능을 적용하여 사용자 실수로 인한 데이터 삭제를 방지하고 복구가 가능하도록 구현했습니다.

## 🧠 기술적 고민 (Technical Challenges)

### "성능과 가독성 사이의 균형"
- 단순 SQL 대신 **Eloquent ORM**을 선택하여 코드의 가독성을 높이고 유지보수를 쉽게 만들었습니다.
- 특히 **Mass Assignment(updateOrCreate)**를 활용하여 중복 데이터를 방지하고 코드의 길이를 60% 이상 단축한 경험이 있습니다.

---

## 💻 실행 방법 (Installation)
1. `composer install`
2. `cp .env.example .env`
3. `php artisan migrate --seed`
4. `php artisan serve`
