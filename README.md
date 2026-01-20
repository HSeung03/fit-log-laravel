# 🏋️‍♂️ My Workout Tracker (운동 기록 관리 서비스)

사용자의 체성분 변화를 추적하고, 자신만의 운동 루틴과 매일의 기록을 관리하는 개인 맞춤형 헬스 케어 서비스입니다.

## 🛠 Tech Stack (사용 기술)
* **Framework**: Laravel 12.44.0
* **Language**: PHP 8.3.6
* **Database**: MySQL
* **Frontend**: Blade Template, FullCalendar API, Tailwind CSS

---

## 🚀 Key Features (주요 기능)

### 1. 사용자 신체 스펙 관리 (User Specs)
* **기초 데이터 저장**: 키(Height), 초기 체중, 근육량, 체지방량을 저장하여 변화의 기준점을 잡습니다.
* **기능 확장**: 기존 사용자(`users`) 테이블에 마이그레이션을 통해 유연하게 필드를 추가했습니다.

### 2. 운동 루틴 템플릿 (Workout Templates)
* **맞춤형 루틴**: '월요일 하체 루틴'과 같이 자주 사용하는 운동 구성을 템플릿으로 저장합니다.
* **JSON 데이터 활용**: 루틴 내용을 JSON 형태로 저장하여 복잡한 운동 목록도 효율적으로 관리합니다.

### 3. 일일 운동 및 식단 기록 (Daily Logs)
* **달력 연동**: 기록된 날짜(`record_date`)를 바탕으로 달력에 활동이 표시됩니다.
* **종합 기록**: 당일 체중, 상세 운동 결과(무게, 횟수 등), 식단 내용을 한 번에 기록합니다.

### 4. 나만의 운동 종목 (Exercises)
* **커스텀 종목**: 사용자가 직접 운동 이름과 카테고리(상체, 하체, 유산소 등)를 등록하여 관리합니다.

---

## 📑 Database Structure (상세 설계)

마이그레이션 파일을 기반으로 설계된 데이터베이스 구조입니다. 모든 테이블은 사용자가 탈퇴할 때 관련 기록이 함께 삭제되도록 `onDelete('cascade')` 설정이 적용되어 있습니다.

### 1. users (기존 테이블 확장)
| Column | Type | Description |
| :--- | :--- | :--- |
| **height** | float | 사용자의 키 |
| **initial_weight** | float | 처음 측정한 체중 |
| **initial_muscle** | float | 처음 측정한 근육량 |
| **initial_fat** | float | 처음 측정한 체지방량 |

### 2. workout_templates (운동 루틴)
| Column | Type | Description |
| :--- | :--- | :--- |
| **user_id** | foreignId | 생성한 사용자 고유 번호 |
| **template_name** | string | 루틴의 이름 (예: 월요일 하체 루틴) |
| **routine_contents** | json | 루틴에 포함된 운동 목록 데이터 |

### 3. workout_logs (일일 기록)
| Column | Type | Description |
| :--- | :--- | :--- |
| **user_id** | foreignId | 기록 작성자 고유 번호 |
| **record_date** | date | 운동을 기록한 날짜 |
| **current_weight** | decimal | 기록 당일의 체중 |
| **workout_results** | json | 운동별 상세 수치 (세트, 무게, 횟수) |
| **diet_content** | text | 식단 및 메모 기록 |

### 4. exercises (운동 종목)
| Column | Type | Description |
| :--- | :--- | :--- |
| **user_id** | foreignId | 등록한 사용자 고유 번호 |
| **name** | string | 운동 명칭 (예: 벤치프레스) |
| **category** | string | 부위별 분류 (상체, 하체, 유산소 등) |

---
<img width="640" height="375" alt="image" src="https://github.com/user-attachments/assets/f62ae4e5-72b5-4206-8b49-9017a9710f7f" />
<img width="691" height="664" alt="image" src="https://github.com/user-attachments/assets/4589aec5-2c57-4449-b070-b1e50128e78d" />

<img width="1123" height="1257" alt="image" src="https://github.com/user-attachments/assets/f7c7b1d1-494b-41a7-b446-47359ac9adc9" />
메인페이지
<img width="1144" height="1247" alt="image" src="https://github.com/user-attachments/assets/1f8c920e-b1d2-4163-9e8a-dfb614951a22" />
회원가입
<img width="1141" height="1261" alt="image" src="https://github.com/user-attachments/assets/5326e142-d3d8-4e4e-b51d-cd1f2a737f5e" />
로그인
<img width="1142" height="1273" alt="image" src="https://github.com/user-attachments/assets/b2bf986f-63e3-402d-8ff0-5122c5090a6a" />
대쉬보드
<img width="1143" height="1274" alt="image" src="https://github.com/user-attachments/assets/edf9734a-3040-4a7a-a246-2b164898e4ab" />
운동기록
<img width="1142" height="1228" alt="image" src="https://github.com/user-attachments/assets/7c6d98b1-61c4-42ee-bf1d-8ffde77e06c4" />
기록확인
<img width="1142" height="994" alt="image" src="https://github.com/user-attachments/assets/34b0b206-e15a-4cb9-a831-bed77b2cdff7" />
상세확인
<img width="1142" height="1007" alt="image" src="https://github.com/user-attachments/assets/279b6216-f3ed-43c5-812d-6ed6b2c9b274" />
운동추가

