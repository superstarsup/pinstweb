---
description: 배포 전 체크리스트 — 테스트 문자열, include 경로, 빈 파일, XAMPP 잔존, Tailwind CDN 등을 일괄 점검
---

# /deploy-check — 배포 전 점검

SFTP 로 원격 서버에 자동 업로드되기 때문에 저장 즉시 프로덕션에 반영된다. 커밋/푸시 전 아래 항목을 모두 점검한다.

## 점검 항목

### 1. 테스트 문자열 잔존 (이슈 #2 유형)

`sections/**/*.php`, `index.php`, `*.html` 에서 다음 패턴을 grep:
- 반복 자음/모음: `ㄱㄱㄱ+`, `ㄴㄴㄴ+`, `ㅇㅇㅇ+`, `ㅋㅋㅋ+`, `ㅎㅎㅎ+`
- 반복 알파벳: `s{4,}`, `a{4,}`, `x{3,}`
- 의심 문자열: `test`, `todo`, `fixme`, `xxx`, `lorem`, `asdf`, `qwer`

각 매치를 `파일:라인` 으로 보고.

### 2. Include 경로 해결성 (이슈 #1 유형)

`/sync-index` 의 로직을 적용. `index.php` 에서 include 하는 경로가 실제 파일 시스템에서 해결 가능한지 확인. 실패 시 **배포 차단** 권고.

### 3. 빈 파일 (이슈 #7 유형)

프로젝트 내 `*.php`, `*.html`, `*.js`, `*.css` 중 크기 0 또는 공백만 있는 파일 나열.

### 4. XAMPP / Bitnami 잔존 (이슈 #4 유형)

다음 경로/파일이 있으면 플래그:
- `applications.html`
- `bitnami.css`
- `dashboard/`
- 파일 내에 `bitnami`, `xampp`, `apache friends` 문자열

### 5. Tailwind CDN 경고 (이슈 #5 유형)

`cdn.tailwindcss.com` 사용처를 찾아 '프로덕션에서는 빌드 산출물 권장' 경고 표시. 차단은 아님.

### 6. 민감 파일 체크

다음 패턴이 업로드 범위에 포함되면 경고:
- `.env*`, `*.key`, `*.pem`, `id_rsa*`
- `.vscode/sftp.json` 의 `password` 필드가 평문이면 경고

### 7. TODO / 디버그 흔적

- `console.log`, `var_dump`, `print_r`, `die(` 호출
- `debugger;` 줄

## 출력 형식

각 항목별 상태 배지 + 문제 위치 리스트:

```
[BLOCK] 1. 테스트 문자열: 2건
  - sections/banner/banner.php:7  "sssss"
  - sections/banner/banner.php:9  "ㄴㄴㄴㄴㄴ11"
[OK]    2. Include 경로: 모두 해결됨
[WARN]  5. Tailwind CDN: index.php:7
...
```

최하단에 전체 판정:
- `BLOCK` 이 하나라도 있으면 **배포 금지**
- `WARN` 만 있으면 **주의 배포**
- 모두 `OK` 면 **배포 가능**

## 규칙

- 자동 수정하지 않는다. 보고만.
- 실행 시간이 길어지면 (수백 파일 이상) 섹션 단위로 병렬 grep.
- `.claude/`, `.git/`, `node_modules/`, `dashboard/` (있으면) 는 스캔 제외.
