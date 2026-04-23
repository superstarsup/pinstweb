---
description: index.php include 와 sections/* 실제 디렉토리를 대조해 누락/고아를 리포트
---

# /sync-index — include 동기화 검증

## 절차

1. **파일 위치 파악** — CWD 에서 `index.php` 와 `sections/` 를 찾는다. 없으면 `..` 에서 탐색. 둘이 서로 다른 레벨에 있으면(예: `index.php` 는 상위, `sections/` 는 하위) 해당 사실을 **경고**로 보고 (이슈 #1 참조 — include 가 실제로 해결되지 않을 수 있음).

2. **include 추출** — `index.php` 에서 `<?php include 'sections/<X>/<X>.php'; ?>` 패턴을 모두 추출해 리스트 A 를 만든다. 경로 변형(`./sections/`, 다른 quote 등) 도 관대하게 매치.

3. **실제 섹션 스캔** — `sections/*/` 디렉토리 목록을 리스트 B 로. 각 디렉토리에 같은 이름의 `.php` 파일이 있는지도 확인.

4. **대조 리포트** — 다음 4가지를 구분해서 보여준다:
   - **정상**: A ∩ B, 파일도 존재
   - **Include 누락**: B 에 있으나 A 에 없음 (폴더만 있고 include 안 됨)
   - **고아 Include**: A 에 있으나 B 에 없음 (include 는 있는데 파일/폴더 없음)
   - **파일 누락**: 폴더는 있으나 내부 `<name>.php` 없음

5. **경로 해결성 점검** — 리스트 A 의 각 include 가 실제로 `index.php` 기준 상대 경로로 열 수 있는지 확인. `index.php` 가 상위, `sections/` 가 하위에 있는 경우 모두 실패로 표시.

6. **출력** — 마크다운 표 1개로 요약. 수정 제안(추가/삭제할 include 라인)도 같이. **자동 수정은 하지 않는다** — 사용자 확인 후 별도 편집.

## 규칙

- `index.html` 은 무시 (레거시).
- 주석 처리된 include (`<!-- ... -->` 또는 `// ...`) 는 비활성으로 간주하고 별도 섹션에 보고.
