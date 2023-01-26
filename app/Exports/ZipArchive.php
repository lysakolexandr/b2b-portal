<?php
class ZipArchive implements Countable {
/* Eigenschaften */
public readonly int $lastId;
public readonly int $status;
public readonly int $statusSys;
public readonly int $numFiles;
public readonly string $filename;
public readonly string $comment;
/* Methoden */
public addEmptyDir(string $dirname, int $flags = 0): bool
public addFile(
    string $filepath,
    string $entryname = "",
    int $start = 0,
    int $length = 0,
    int $flags = ZipArchive::FL_OVERWRITE
): bool
public addFromString(string $name, string $content, int $flags = ZipArchive::FL_OVERWRITE): bool
public addGlob(string $pattern, int $flags = 0, array $options = []): array|false
public addPattern(string $pattern, string $path = ".", array $options = []): array|false
public clearError(): void
public close(): bool
public count(): int
public deleteIndex(int $index): bool
public deleteName(string $name): bool
public extractTo(string $pathto, array|string|null $files = null): bool
public getArchiveComment(int $flags = 0): string|false
public getCommentIndex(int $index, int $flags = 0): string|false
public getCommentName(string $name, int $flags = 0): string|false
public getExternalAttributesIndex(
    int $index,
    int &$opsys,
    int &$attr,
    int $flags = ?
): bool
public getExternalAttributesName(
    string $name,
    int &$opsys,
    int &$attr,
    int $flags = 0
): bool
public getFromIndex(int $index, int $len = 0, int $flags = 0): string|false
public getFromName(string $name, int $len = 0, int $flags = 0): string|false
public getNameIndex(int $index, int $flags = 0): string|false
public getStatusString(): string
public getStream(string $name): resource|false
public getStreamIndex(int $index, int $flags = 0): resource|false
public getStreamName(string $name, int $flags = 0): resource|false
public static isCompressionMethodSupported(int $method, bool $enc = true): bool
public static isEncryptionMethodSupported(int $method, bool $enc = true): bool
public locateName(string $name, int $flags = 0): int|false
public open(string $filename, int $flags = 0): bool|int
public registerCancelCallback(callable $callback): bool
public registerProgressCallback(float $rate, callable $callback): bool
public renameIndex(int $index, string $new_name): bool
public renameName(string $name, string $new_name): bool
public replaceFile(
    string $filepath,
    int $index,
    int $start = 0,
    int $length = 0,
    int $flags = 0
): bool
public setArchiveComment(string $comment): bool
public setCommentIndex(int $index, string $comment): bool
public setCommentName(string $name, string $comment): bool
public setCompressionIndex(int $index, int $method, int $compflags = 0): bool
public setCompressionName(string $name, int $method, int $compflags = 0): bool
public setEncryptionIndex(int $index, int $method, ?string $password = null): bool
public setEncryptionName(string $name, int $method, ?string $password = null): bool
public setExternalAttributesIndex(
    int $index,
    int $opsys,
    int $attr,
    int $flags = 0
): bool
public setExternalAttributesName(
    string $name,
    int $opsys,
    int $attr,
    int $flags = 0
): bool
public setMtimeIndex(int $index, int $timestamp, int $flags = 0): bool
public setMtimeName(string $name, int $timestamp, int $flags = 0): bool
public setPassword(string $password): bool
public statIndex(int $index, int $flags = 0): array|false
public statName(string $name, int $flags = 0): array|false
public unchangeAll(): bool
public unchangeArchive(): bool
public unchangeIndex(int $index): bool
public unchangeName(string $name): bool
}
