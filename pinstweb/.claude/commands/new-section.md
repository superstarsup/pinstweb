---
description: 새 섹션 폴더/파일을 스캐폴드하고 index.php 에 include 라인을 추가한다
---

# /new-section — 섹션 스캐폴드

`$ARGUMENTS` 는 섹션 이름 (예: `faq`, `contact`). 소문자 영문/숫자/하이픈만.

## 절차

1. **이름 검증** — `$ARGUMENTS` 가 비었거나 `[a-z0-9-]+` 매치 실패하면 중단하고 사용자에게 올바른 이름을 요청.

2. **루트 탐지** — `index.php` 위치를 찾는다. CWD 에 있으면 CWD, 없으면 `..` 에서 탐색. 둘 다 없으면 중단. `sections/` 디렉토리 역시 같은 방식으로 찾는다. 두 경로가 어느 수준에 있는지 사용자에게 보고.

3. **중복 확인** — `sections/<name>/` 가 이미 있으면 중단. 덮어쓰지 않음.

4. **파일 생성** — `sections/<name>/<name>.php` 를 아래 템플릿으로 작성. `<Name>` 은 첫 글자 대문자.

   ```php
   <!-- <Name> -->
   <section id="pinst-<name>" class="px-4 pb-10">
     <div class="max-w-[1000px] mx-auto flex flex-col md:flex-row">
       <!-- 좌측 -->
       <div class="flex flex-col items-center justify-center w-full md:w-2/5 py-10 gap-4 text-gray-700 font-medium text-base">
         <Name>
       </div>
       <!-- 우측 -->
       <div class="w-full md:w-3/5 p-6 text-sm text-gray-700 space-y-4 flex flex-col justify-center">
         <p>여기에 내용을 작성합니다.</p>
       </div>
     </div>
   </section>
   ```

5. **index.php 수정** — 마지막 `<?php include 'sections/...' ?>` 줄 바로 아래에 다음 줄을 추가:

   ```php
     <?php include 'sections/<name>/<name>.php'; ?>
   ```

   기존 include 블록 들여쓰기(공백 2칸)를 동일하게 유지. 마지막 기존 include 를 `Read` 로 확인 후 `Edit` 으로 그 줄을 \"기존 줄 + 새 줄\" 로 치환.

6. **보고** — 생성된 파일 경로와 수정된 `index.php` 라인 번호를 사용자에게 보여준다. 커밋은 하지 않는다 (사용자가 `/commit` 으로 따로 수행).

## 규칙

- 예약어(`nav`, `banner`) 는 이미 쓰이므로 중복 생성 금지.
- 이미지 플레이스홀더는 넣지 않는다 (사용자가 직접 추가).
- `index.html` (레거시) 은 건드리지 않는다.
