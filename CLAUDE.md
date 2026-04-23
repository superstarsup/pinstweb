# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

**프로젝트명:** PINST WEB
**사용언어:** HTML, JavaScript, PHP, TailwindCSS
**언어설정:** UTF-8
**반응형 웹:** 적용 (모바일 / 태블릿 / 데스크탑)

## 디렉토리 구조

```
web/                       ← git repo root (이 저장소)
├── CLAUDE.md              ← 이 파일
└── pinstweb/              ← 실제 웹앱 루트 (SFTP 배포 context)
    ├── index.php          ← source-of-truth (셸: head/body + 섹션 include + nav JS)
    ├── sections/
    │   ├── nav/nav.php
    │   ├── banner/banner.php
    │   ├── introduce1/introduce1.php
    │   ├── introduce2/introduce2.php
    │   └── introduce3/introduce3.php
    ├── .vscode/sftp.json  ← 원격 FTP 배포 설정
    └── .claude/           ← 에이전트 하네스 (commands/, agents/)
```

- **source-of-truth 는 `pinstweb/index.php` 하나**. 루트(`web/`)는 저장소 컨테이너일 뿐 배포되지 않는다.
- SFTP 익스텐션이 `pinstweb/` 를 원격으로 동기화한다. `pinstweb/` 바깥 파일은 프로덕션에 노출되지 않는다.

## 아키텍처

PHP include 기반 다중 파일 구조. `index.php` 가 HTML 셸(head/body, nav 반응형 style, inline JS)을 담당하고, 각 섹션은 `sections/<name>/<name>.php` 에서 순수 마크업만 정의한다.

**섹션 순서 (pinstweb/index.php include 순):**
- `sections/nav/nav.php` — 고정 상단 네비게이션 + 모바일 햄버거 메뉴
- `sections/banner/banner.php` — 메인 배너 (배경 이미지 + CTA)
- `sections/introduce1/introduce1.php` — 실시간 모니터링
- `sections/introduce2/introduce2.php` — 드라이버 자동 검색 (`#driver-count` 는 AJAX 대기)
- `sections/introduce3/introduce3.php` — 오류없는 스캔설정

**index.php 인라인 JS:**
- `setNavPadding()` — md(≥768px) 에서 nav 좌우 200px 패딩, 미만에서 0
- 햄버거 메뉴 토글 (`#menu-toggle` → `#nav-menu`)
- 앵커 링크 smooth scroll (nav 높이 50px offset)

## 섹션 컨벤션

- 파일 경로: `pinstweb/sections/<name>/<name>.php`
- 내용: 순수 HTML. `<!DOCTYPE>`, `<html>`, `<head>`, `<body>`, `<script>` 미포함 (index.php 가 담당)
- 시작 주석: `<!-- <Name> -->`
- 래퍼: 단일 `<section>` + `max-w-[1000px] mx-auto` 컨테이너
- 새 섹션은 `/new-section <name>` 으로 스캐폴드 (index.php 에 include 라인 자동 추가)

## 개발 환경 및 배포

빌드 시스템 없음. VS Code SFTP 익스텐션이 `pinstweb/` 저장 시 원격으로 자동 업로드 (uploadOnSave).

- **원격 호스트:** `pinst.kr` (FTP)
- **원격 경로:** `/web`
- **설정 파일:** `pinstweb/.vscode/sftp.json`

로컬 실행: `pinstweb/` 에서 `php -S localhost:8000` 또는 Live Server.

## TailwindCSS

CDN (`https://cdn.tailwindcss.com`) 로드. 별도 설정 파일 없음. 커스텀 색상은 인라인 (예: `bg-[#5b8db8]`).

CDN 로드는 프로덕션 콘솔 경고 및 purge 불가 문제가 있으므로, 추후 Tailwind CLI 빌드 산출물로 전환 예정 (이슈 #5).

## 레이아웃 컨벤션

- 최대 콘텐츠 너비: `max-w-[1000px] mx-auto`
- 좌우 여백 (데스크탑 nav): `pl-[200px]` / `pr-[200px]`
- 반응형 분기점: `md:` (Tailwind 기본 768px)
- 섹션 레이아웃: 모바일 `flex-col` → 데스크탑 `md:flex-row`, 좌측 2/5 + 우측 3/5

## 에이전트 하네스 (Claude Code)

`pinstweb/.claude/commands/` 와 `pinstweb/.claude/agents/` 에 이 저장소 전용 도구가 정의되어 있다. 상세는 이슈 #8 참조.

**슬래시 커맨드**
- `/commit [#N]` — 이슈 번호 필수 커밋. 번호 없으면 `gh issue list` 로 확인 후 진행
- `/new-section <name>` — `sections/<name>/<name>.php` 스캐폴드 + `index.php` include 자동 추가
- `/sync-index` — `index.php` include 와 `sections/` 실제 폴더 대조. 누락/고아 리포트
- `/deploy-check` — 배포 전 일괄 점검 (테스트 문자열, include 해결성, 빈 파일, XAMPP 잔존, Tailwind CDN, 민감 파일, 디버그 흔적)

**서브에이전트**
- `code-reviewer` — 독립 리뷰어. `Agent(subagent_type=code-reviewer)` 로 호출
